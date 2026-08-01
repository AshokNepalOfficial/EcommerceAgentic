<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable()->index();
            
            // Item details
            $table->foreignId('product_variant_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('bundle_product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->enum('item_type', ['variant', 'bundle'])->default('variant');
            
            // Platform & Source Tracking
            $table->enum('platform', ['web', 'messenger', 'whatsapp', 'instagram', 'tiktok', 'api'])->default('web');
            $table->string('source_id')->nullable()->index();
            $table->string('device_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            
            $table->timestamps();
            
            $table->unique(['user_id', 'product_variant_id', 'platform', 'source_id'], 'wishlist_user_variant_platform');
            $table->unique(['session_id', 'product_variant_id', 'platform', 'source_id'], 'wishlist_session_variant_platform');
        });
    }
    public function down(): void { Schema::dropIfExists('wishlists'); }
};