<?php
namespace App\Services;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Services\BaseService;
use App\Http\Tools\ParamTools;
use App\Mail\OrderUpdateEmail;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\OrderResource;

class SVOrder extends BaseService
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getListOrder($params)
    {
        $perPage       = ParamTools::get_value($params, 'per_page', 10);
        $search        = ParamTools::get_value($params, 'search', '');
        $sortField     = ParamTools::get_value($params, 'sort_field', 'updated_at');
        $sortDirection = ParamTools::get_value($params, 'sort_direction', 'desc');

        $query = Order::query()
            ->withCount('items')
            ->with('user.customer')
            ->where('id', 'like', "%{$search}%")
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        return OrderResource::collection($query);
    }

    public function viewOrder(Order $order)
    {
        $order->load('items.product');

        return new OrderResource($order);
    }

    public function getStatuses()
    {
        return OrderStatus::getStatuses();
    }

    public function changeStatus(Order $order, $status)
    {
        $order->status = $status;
        $order->save();

        if ($status === OrderStatus::Cancelled->value) {
            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product && $product->quantity !== null) {
                    $product->quantity += $item->quantity;
                    $product->save();
                }
            }
        }

        Mail::to($order->user)->send(new OrderUpdateEmail($order));

        return response('', 200);
    }

}