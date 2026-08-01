<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Order extends Model {
    use SoftDeletes;
    protected $fillable = ['user_id','session_id','order_number','subtotal','tax_amount','shipping_cost','discount_amount','grand_total','items_snapshot','customer_name','customer_email','customer_phone','shipping_address','billing_address','platform','source_id','device_id','ip_address','user_agent','status','payment_status','payment_method','payment_id','notes','admin_notes'];
    protected $casts = ['items_snapshot' => 'array','shipping_address' => 'array','billing_address' => 'array'];
    public function items() { return $this->hasMany(OrderItem::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function scopePlatform($q, $p) { return $q->where('platform', $p); }
}