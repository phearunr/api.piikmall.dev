<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tc_history', function (Blueprint $table) {
            $table->id();
            $table->dateTime('printed_on');
            $table->integer('branch');
            $table->date('statement_period_start');
            $table->date('statement_period_end');
            $table->string('currency');
            $table->date('open_date');
            $table->integer('account_number');
            $table->double('current_available_balance', 8, 2);
            $table->double('current_ledger_balance', 8, 2);
            $table->json('customer_info');
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
        Schema::dropIfExists('tc_history');
    }
};
