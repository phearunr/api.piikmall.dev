<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tc_history_details', function (Blueprint $table) {
            $table->string('bank_ref');
            $table->foreignId('tc_history_id')->index()->constrained('tc_history');
            $table->integer('apv');
            $table->date('date')->nullable();
            $table->string('transaction')->nullable();
            $table->longText('details')->nullable();
            $table->double('withdrawal', 12, 2)->nullable();
            $table->double('deposit', 12, 2)->nullable();
            $table->double('balance', 12, 2)->nullable();
            $table->string('user_id')->nullable();
            $table->string('value_dt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tc_history_details');
    }
};
