<?php
namespace App\Services;

use App\Enums\OrderStatus;
use App\Http\Resources\OrderResource;
use App\Services\BaseService;
use App\Http\Tools\ParamTools;
use App\Http\Resources\ProductListResource;
use App\Models\Order;

class SVOrder extends BaseService
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getListOrder()
    {
        $perPage = request('per_page', 10);
        $search = request('search', '');
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');

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

    // public function changeStatus(Order $order, $status)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $order->status = $status;
    //         $order->save();

    //         if ($status === OrderStatus::Cancelled->value) {
    //             foreach ($order->items as $item) {
    //                 $product = $item->product;
    //                 if ($product && $product->quantity !== null) {
    //                     $product->quantity += $item->quantity;
    //                     $product->save();
    //                 }
    //             }
    //         }
    //         Mail::to($order->user)->send(new OrderUpdateEmail($order));
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }

    //     DB::commit();

    //     return response('', 200);
    // }

}