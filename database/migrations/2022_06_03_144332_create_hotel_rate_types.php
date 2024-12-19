<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelRateTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_rate_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('long_name')->nullable();
            $table->string('rate_types_id');
            $table->bigInteger('hotel_detail_id')->index();
            $table->string('default')->nullable();
            $table->string('breakfast')->nullable();
            $table->string('lunch')->nullable();
            $table->string('dinner')->nullable();
            $table->string('must_package')->nullable();
            $table->string('non_refundable')->nullable();
            $table->double('markup_rate')->default(0);
            $table->string('is_offer')->nullable();
            $table->string('member_extra')->nullable();
            $table->string('rate_code')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->text('deposit_policy')->nullable();

            $table->index(['hotel_detail_id', 'name']);
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
        Schema::dropIfExists('hotel_rate_types');
    }
}
