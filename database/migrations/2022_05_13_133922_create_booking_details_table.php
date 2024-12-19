<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hotel_id')->default(0);
            $table->bigInteger('hotel_smith_property_id')->default(0);
            $table->string('hotel_smith_room_id')->nullable();
            $table->string('hotel_rate_type_id')->nullable();
            $table->bigInteger('user_id')->default(0)->index();
            $table->enum('status', ['pending', 'confirmed', 'canceled'])->index()->default('pending');
            $table->string('total_amount_inc_tax')->nullable();
            $table->string('total_amount_ex_tax')->nullable();
            $table->integer('currency_id')->default(0);
            $table->timestamp('date_to')->nullable();
            $table->timestamp('date_from')->nullable();
            $table->integer('nights')->default(0);
            $table->json('meta')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_details');
    }
}
