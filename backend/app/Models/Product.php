<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model {
    protected $fillable = ['name','slug','short_description','description','type','variant_slug_template','meta_title','meta_description','meta_keywords','og_title','og_description','og_image','category_id','brand_id','status'];
    public function category() { return $this->belongsTo(Category::class); }
    public function brand() { return $this->belongsTo(Brand::class); }
    public function variants() { return $this->hasMany(ProductVariant::class); }
    public function groupedProducts() { return $this->belongsToMany(Product::class, 'grouped_product_items', 'parent_product_id', 'child_product_id')->withPivot('sort_order'); }
    public function bundleComponents() { return $this->belongsToMany(ProductVariant::class, 'bundle_product_items', 'bundle_product_id', 'component_variant_id')->withPivot('quantity'); }
}