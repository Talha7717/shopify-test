<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_name',
        'customer_email',
        'order_price',
        'order_create',
        'defualt_address',
        'shipping_address',
        'billing_address',

    ];
}
