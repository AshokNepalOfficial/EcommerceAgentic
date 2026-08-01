<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProductImage extends Model {
    protected $fillable = ['product_variant_id','image_url','alt_text','title','is_primary','sort_order'];
    public function variant() { return $this->belongsTo(ProductVariant::class); }
}