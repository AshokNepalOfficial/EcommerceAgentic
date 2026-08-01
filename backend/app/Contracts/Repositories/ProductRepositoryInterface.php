<?php
namespace App\Contracts\Repositories;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
interface ProductRepositoryInterface {
    public function find(int $id): Product;
    public function getPaginatedForListing(array $filters = [], int $perPage = 20): LengthAwarePaginator;
}