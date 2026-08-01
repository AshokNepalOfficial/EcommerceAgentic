<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('bundle_product_id')->nullable()->constrained('products')->onDelete('set null');
            
            $table->enum('item_type', ['variant', 'bundle'])->default('variant');
            
            // Snapshot of the item at the time of purchase
            $table->string('name_snapshot');
            $table->decimal('price_snapshot', 12, 2);
            $table->integer('quantity');
            $table->json('attributes_snapshot')->nullable(); // Store selected attributes
            $table->json('component_snapshot')->nullable(); // For bundles
            
            // Platform & Source Tracking
            $table->enum('platform', ['web', 'messenger', 'whatsapp', 'instagram', 'tiktok', 'api'])->default('web');
            $table->string('source_id')->nullable()->index();
            
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('order_items'); }
};