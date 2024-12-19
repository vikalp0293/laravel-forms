<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterBookingDetailsAddingSomeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_details', function (Blueprint $table) {
            $table->integer('children')->default(0)->after('nights');
            $table->bigInteger('invoice_id')->default(0)->after('user_id');
            $table->string('currency_code')->default('USD')->after('currency_id');
            $table->double('total_tax')->default(0)->after('total_amount_ex_tax');
            $table->string('smith_booking_id')->nullable()->after('hotel_smith_room_id');
            $table->renameColumn('hotel_id', 'hotel_detail_id');

            DB::statement("ALTER TABLE booking_details MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled') default 'pending' ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_details', function (Blueprint $table) {
            $table->dropColumn('children');
            $table->dropColumn('currency_code');
            $table->dropColumn('invoice_id');
            $table->dropColumn('total_tax');
            $table->dropColumn('smith_booking_id');
            $table->renameColumn('hotel_detail_id', 'hotel_id');
        });
    }
}
