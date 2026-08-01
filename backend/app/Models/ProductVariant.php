<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProductVariant extends Model {
    protected $fillable = ['product_id','title','slug','description','sku','price','stock','thumbnail','weight','custom_meta','status','meta_title','meta_description','meta_keywords','og_title','og_description','og_image','canonical_url'];
    protected $casts = ['custom_meta' => 'array'];
    public function product() { return $this->belongsTo(Product::class); }
    public function images() { return $this->hasMany(ProductImage::class)->orderBy('sort_order'); }
    public function attributeValues() { return $this->belongsToMany(AttributeValue::class, 'product_variant_attribute_values'); }
    public function getPrimaryImageAttribute() { return $this->images()->where('is_primary', true)->first() ?? $this->images()->first() ?? (object) ['image_url' => $this->thumbnail, 'alt_text' => $this->title]; }
    public function getSeoTitleAttribute() { return $this->meta_title ?? $this->product->meta_title ?? $this->title; }
    public function getSeoDescriptionAttribute() { return $this->meta_description ?? $this->product->meta_description ?? $this->description ?? $this->product->short_description; }
    public function getSeoOgImageAttribute() { return $this->og_image ?? $this->product->og_image ?? $this->primary_image->image_url ?? asset('default-og.jpg'); }
}