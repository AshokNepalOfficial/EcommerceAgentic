<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OrderItem extends Model {
    protected $fillable = ['order_id','product_variant_id','bundle_product_id','item_type','name_snapshot','price_snapshot','quantity','attributes_snapshot','component_snapshot','platform','source_id'];
    protected $casts = ['attributes_snapshot' => 'array','component_snapshot' => 'array'];
    public function order() { return $this->belongsTo(Order::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
    public function bundle() { return $this->belongsTo(Product::class, 'bundle_product_id'); }
}