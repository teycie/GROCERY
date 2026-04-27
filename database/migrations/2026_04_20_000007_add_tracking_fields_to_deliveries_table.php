<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddTrackingFieldsToDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            if (!Schema::hasColumn('deliveries', 'fulfillment_type')) {
                $table->string('fulfillment_type')->default('delivery')->after('quantity');
            }

            if (!Schema::hasColumn('deliveries', 'payment_mode')) {
                $table->string('payment_mode')->nullable()->after('fulfillment_type');
            }

            if (!Schema::hasColumn('deliveries', 'tracking_status')) {
                $table->string('tracking_status')->default('pending')->after('status');
            }
        });

        DB::table('deliveries')->whereNull('tracking_status')->update([
            'tracking_status' => 'pending',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            if (Schema::hasColumn('deliveries', 'tracking_status')) {
                $table->dropColumn('tracking_status');
            }

            if (Schema::hasColumn('deliveries', 'payment_mode')) {
                $table->dropColumn('payment_mode');
            }

            if (Schema::hasColumn('deliveries', 'fulfillment_type')) {
                $table->dropColumn('fulfillment_type');
            }
        });
    }
}
