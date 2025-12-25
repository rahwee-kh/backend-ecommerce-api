<?php

namespace App\Services;

use http\Env\Request;
use App\Models\Country;
use App\Models\Customer;
use App\Enums\AddressType;
use App\Enums\CustomerStatus;
use App\Services\BaseService;
use App\Http\Tools\ParamTools;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CountryResource;
use App\Http\Resources\CustomerResource;

class SVCustomer extends BaseService
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($params)
    {
        $perPage       = ParamTools::get_value($params, 'per_page', 10);
        $search        = ParamTools::get_value($params, 'search', '');
        $sortField     = ParamTools::get_value($params, 'sort_field', 'updated_at');
        $sortDirection = ParamTools::get_value($params, 'sort_direction', 'desc');

        $query = Customer::query()
            ->with('user')
            ->orderBy("customers.$sortField", $sortDirection);
        if ($search) {
            $query
                ->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%")
                ->join('users', 'customers.user_id', '=', 'users.id')
                ->orWhere('users.email', 'like', "%{$search}%")
                ->orWhere('customers.phone', 'like', "%{$search}%");
        }

        $paginator = $query->paginate($perPage);

        return CustomerResource::collection($paginator);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Customer     $customer
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $customerData               = $request->validated();
        $customerData['updated_by'] = $request->user()->id;
        $customerData['status']     = $customerData['status'] ? CustomerStatus::Active->value : CustomerStatus::Disabled->value;
        $shippingData               = $customerData['shippingAddress'];
        $billingData                = $customerData['billingAddress'];

   
        $customer->update($customerData);

        if ($customer->shippingAddress) {
            $customer->shippingAddress->update($shippingData);
        } else {
            $shippingData['customer_id'] = $customer->user_id;
            $shippingData['type'] = AddressType::Shipping->value;
            CustomerAddress::create($shippingData);
        }

        if ($customer->billingAddress) {
            $customer->billingAddress->update($billingData);
        } else {
            $billingData['customer_id'] = $customer->user_id;
            $billingData['type'] = AddressType::Billing->value;
            CustomerAddress::create($billingData);
        }
        
        return new CustomerResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->noContent();
    }

    public function countries()
    {
        return CountryResource::collection(Country::query()->orderBy('name', 'asc')->get());
    }
}