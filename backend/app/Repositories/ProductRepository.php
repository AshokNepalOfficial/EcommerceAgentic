<?php
namespace App\Repositories;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
class ProductRepository implements ProductRepositoryInterface {
    public function find(int $id): Product {
        return Product::with(['brand','category'])->findOrFail($id);
    }
    public function getPaginatedForListing(array $filters = [], int $perPage = 20): LengthAwarePaginator {
        $query = Product::query()->where('status',true)->with(['category','brand'])->withAggregate('variants as min_price','price','min');
        if (!empty($filters['category_id'])) $query->where('category_id', $filters['category_id']);
        if (!empty($filters['brand_id'])) $query->where('brand_id', $filters['brand_id']);
        if (!empty($filters['search'])) $query->where('name','LIKE','%'.$filters['search'].'%');
        switch ($filters['sort'] ?? 'newest') {
            case 'price_low': $query->orderBy('min_price','asc'); break;
            case 'price_high': $query->orderBy('min_price','desc'); break;
            default: $query->orderBy('created_at','desc');
        }
        return $query->paginate($perPage);
    }
}