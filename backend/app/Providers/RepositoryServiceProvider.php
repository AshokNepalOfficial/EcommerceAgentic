<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Contracts\Repositories\ProductVariantRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\CartRepositoryInterface;
use App\Contracts\Repositories\WishlistRepositoryInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Services\CartServiceInterface;
use App\Contracts\Services\WishlistServiceInterface;
use App\Contracts\Services\CheckoutServiceInterface;
use App\Contracts\Services\VariantGeneratorServiceInterface;
use App\Contracts\Services\SeoServiceInterface;
use App\Repositories\ProductVariantRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CartRepository;
use App\Repositories\WishlistRepository;
use App\Repositories\OrderRepository;
use App\Services\CartService;
use App\Services\WishlistService;
use App\Services\CheckoutService;
use App\Services\VariantGeneratorService;
use App\Services\SeoService;
class RepositoryServiceProvider extends ServiceProvider {
    public function register(): void {
        $this->app->bind(ProductVariantRepositoryInterface::class, ProductVariantRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(WishlistRepositoryInterface::class, WishlistRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(CartServiceInterface::class, CartService::class);
        $this->app->bind(WishlistServiceInterface::class, WishlistService::class);
        $this->app->bind(CheckoutServiceInterface::class, CheckoutService::class);
        $this->app->bind(VariantGeneratorServiceInterface::class, VariantGeneratorService::class);
        $this->app->bind(SeoServiceInterface::class, SeoService::class);
    }
    public function boot(): void {}
}