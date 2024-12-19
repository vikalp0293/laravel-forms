<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->default(0)->index();
            $table->bigInteger('booking_id')->default(0);
            $table->string('payment_type')->nullable();
            $table->string('url')->nullable();
            $table->string('pdf_url')->nullable();
            $table->string('currency')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->string('payment_intent')->nullable();
            $table->boolean('paid')->default(false);

            $table->softDeletes();
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
        Schema::dropIfExists('invoices');
    }
}
