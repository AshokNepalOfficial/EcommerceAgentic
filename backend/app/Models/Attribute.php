<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Attribute extends Model {
    protected $fillable = ['name','slug','type','is_visible_on_frontend','is_used_in_variation','sort_order'];
    public function values() { return $this->hasMany(AttributeValue::class); }
}