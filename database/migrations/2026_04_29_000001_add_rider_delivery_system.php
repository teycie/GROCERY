<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRiderDeliverySystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add rider fields to deliveries table
        Schema::table('deliveries', function (Blueprint $table) {
            if (!Schema::hasColumn('deliveries', 'rider_id')) {
                $table->foreignId('rider_id')->nullable()->after('seller_id')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('deliveries', 'rider_assigned_at')) {
                $table->timestamp('rider_assigned_at')->nullable()->after('rider_id');
            }
            if (!Schema::hasColumn('deliveries', 'picked_up_at')) {
                $table->timestamp('picked_up_at')->nullable()->after('rider_assigned_at');
            }
            if (!Schema::hasColumn('deliveries', 'on_delivery_at')) {
                $table->timestamp('on_delivery_at')->nullable()->after('picked_up_at');
            }
        });

        // Create delivery_assignments table for tracking assignment history
        if (!Schema::hasTable('delivery_assignments')) {
            Schema::create('delivery_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('delivery_id')->constrained()->onDelete('cascade');
                $table->foreignId('rider_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
                $table->enum('status', ['assigned', 'accepted', 'picked_up', 'on_delivery', 'delivered', 'cancelled'])->default('assigned');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_assignments');

        Schema::table('deliveries', function (Blueprint $table) {
            if (Schema::hasColumn('deliveries', 'on_delivery_at')) {
                $table->dropColumn('on_delivery_at');
            }
            if (Schema::hasColumn('deliveries', 'picked_up_at')) {
                $table->dropColumn('picked_up_at');
            }
            if (Schema::hasColumn('deliveries', 'rider_assigned_at')) {
                $table->dropColumn('rider_assigned_at');
            }
            if (Schema::hasColumn('deliveries', 'rider_id')) {
                $table->dropForeign(['rider_id']);
                $table->dropColumn('rider_id');
            }
        });
    }
}
