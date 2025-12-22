<?php

namespace App\Enums;

class Constants {
    const TRANSLATE_COLUMNS = ["name", "value"];
    const SHIPPING = 'shipping';
    const BILLING = 'billing';

    const PAYMENT_PENDING_STATUS = 'pending';
    const PAYMENT_PAID_STATUS = 'paid';
    const PAYMENT_FAILED_STATUS="failed";

    const ORDER_UNPAID = 'unpaid';
    const ORDER_PAID = 'paid';
    const ORDER_COMPLETED = 'completed';
}