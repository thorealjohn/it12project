<?php
// database/migrations/2023_01_01_000004_create_orders_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('user_id')->constrained('users'); // User who created the order
            $table->integer('quantity');
            $table->decimal('water_price', 10, 2)->default(25.00);
            $table->boolean('is_delivery')->default(false);
            $table->decimal('delivery_fee', 10, 2)->default(5.00);
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');
            $table->enum('payment_method', ['cash', 'gcash', 'none'])->default('none');
            $table->string('payment_reference')->nullable(); // For GCash reference
            $table->enum('order_status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('delivery_user_id')->nullable()->constrained('users'); // User who delivered
            $table->timestamp('delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}