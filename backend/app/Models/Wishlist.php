<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Wishlist extends Model {
    protected $fillable = ['user_id','session_id','product_variant_id','bundle_product_id','item_type','platform','source_id','device_id','ip_address','user_agent'];
    public function variant() { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
    public function bundle() { return $this->belongsTo(Product::class, 'bundle_product_id'); }
    public function user() { return $this->belongsTo(User::class); }
    public function scopePlatform($q, $p) { return $q->where('platform', $p); }
}