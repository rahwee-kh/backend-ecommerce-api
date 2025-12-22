<?php

namespace App\Models;

use App\Enums\Constants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';
    protected $fillable = ['first_name', 'last_name', 'phone', 'status'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    private function _getAddresses(): HasOne
    {
        return $this->hasOne(CustomerAddress::class, 'customer_id', 'user_id');
    }

    public function shippingAddress() 
    {
        return $this->_getAddresses()->where('type', '=', Constants::SHIPPING);
    }

    public function billingAddress() 
    {
        return $this->_getAddresses()->where('type', '=', Constants::BILLING);
    }
}
