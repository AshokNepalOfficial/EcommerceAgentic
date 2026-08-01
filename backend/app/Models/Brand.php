<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Brand extends Model {
    protected $fillable = ['name','slug','logo_url','description','status','meta_title','meta_description','meta_keywords','og_title','og_description','og_image'];
    public function products() { return $this->hasMany(Product::class); }
}