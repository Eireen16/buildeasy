<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_id',
        'supplier_id',
        'delivery_method',
        'subtotal',
        'shipping_cost',
        'total',
        'order_status',
        'delivery_name',
        'delivery_phone',
        'delivery_address',
        'delivery_state',
        'delivery_city',
        'delivery_postal_code',
        'stripe_payment_intent_id',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Generate unique order ID
    public static function generateOrderId()
    {
        $date = Carbon::now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', Carbon::today())
                        ->orderBy('id', 'desc')
                        ->first();
        
        $sequence = $lastOrder ? (int)substr($lastOrder->order_id, -3) + 1 : 1;
        
        return 'ORD-' . $date . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

}
