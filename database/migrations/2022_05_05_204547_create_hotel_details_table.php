<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hotel_id')->default(0)->index();

            // Smith API fields
            $table->string('smith_hotel_id')->nullable();
            $table->string('smith_property_id')->default(0)->index();
            $table->string('name')->nullable();
            $table->string('status')->nullable();
            $table->string('health_and_fitness')->nullable();
            $table->string('wifi')->nullable();
            $table->string('swimming_pool')->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('image')->nullable();
            $table->string('hotel_currency')->nullable();
            $table->string('address')->nullable();
            $table->string('city', 108)->nullable();
            $table->string('county', 108)->nullable();
            $table->string('postcode', 108)->nullable();
            $table->string('country', 108)->nullable();
            $table->string('country_name', 108)->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_villa')->default(false);
            $table->integer('star_rating')->nullable(0);
            $table->double('map_lat')->nullable();
            $table->double('map_long')->nullable();
            $table->double('tax_rate')->nullable();
            $table->double('doubles_from')->nullable();
            $table->double('doubles_from_tax_amount')->nullable();
            $table->double('doubles_from_ex_tax')->nullable();
            $table->double('doubles_from_gbp_ex_tax')->nullable();
            $table->double('doubles_from_gbp_inc_tax')->nullable();
            $table->double('doubles_from_inc_tax')->nullable();
            $table->boolean('all_inclusive')->default(false);

            $table->integer('max_child_age')->nullable();
            $table->integer('min_child_age')->nullable();
            $table->integer('max_infant_age')->nullable();

            $table->string('check_in_time')->nullable();
            $table->string('check_out_time')->nullable();
            $table->string('record_updated')->nullable();
            $table->string('style')->nullable();
            $table->string('setting')->nullable();
            $table->text('smith_tip')->nullable();
            $table->text('smith_offer')->nullable();
            $table->text('listing_image')->nullable();
            $table->text('listing_portrait_image')->nullable();
            $table->text('listing_snf_image')->nullable();

            $table->integer('number_of_rooms')->default(0);
            $table->integer('sleeps')->default(0);
            $table->json('badges')->nullable();


            $table->boolean('adult_only')->default(false);

            $table->text('restaurant')->nullable();
            $table->string('dress_code')->nullable();
            $table->text('top_table')->nullable();
            $table->text('last_orders')->nullable();
            $table->text('room_service')->nullable();
            $table->text('hotel_bar')->nullable();
            $table->text('rooms_description')->nullable();
            $table->text('rates_description')->nullable();
            $table->text('facilities')->nullable();
            $table->text('checkout')->nullable();
            $table->text('more_kids')->nullable();
            $table->text('also_need_to_know')->nullable();
            $table->text('favourite_rooms')->nullable();
            $table->text('packing_tips')->nullable();
            $table->text('in_the_know_also')->nullable();
            $table->text('wgobf')->nullable();
            $table->text('useful_information')->nullable();

            $table->json('gallery_images')->nullable();
            $table->json('header_images')->nullable();
            $table->json('mms_header')->nullable();
            $table->json('getting_there')->nullable();
            $table->json('events')->nullable();
            $table->json('cards_accepted')->nullable();
            $table->string('website')->nullable();

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
        Schema::dropIfExists('hotel_details');
    }
}
