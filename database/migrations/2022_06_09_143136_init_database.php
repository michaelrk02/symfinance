<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank', function (Blueprint $table) {
            $table->char('id', 36);
            $table->integer('code');
            $table->string('name', 128);
            $table->text('address');
            $table->integer('deposit_fee');
            $table->integer('withdraw_fee');

            $table->primary('id');
            $table->unique('code');
        });

        Schema::create('account', function (Blueprint $table) {
            $table->char('id', 36);
            $table->char('bank', 36);
            $table->integer('number');
            $table->char('user', 36);
            $table->string('name', 128);
            $table->char('pin', 60);
            $table->integer('balance');

            $table->primary('id');
            $table->unique(['bank', 'number']);
        });

        Schema::create('user', function (Blueprint $table) {
            $table->char('id', 36);
            $table->string('email', 254);
            $table->char('password', 60);
            $table->string('name', 128);

            $table->primary('id');
            $table->unique('email');
        });

        Schema::create('transaction', function (Blueprint $table) {
            $table->char('id', 36);
            $table->dateTime('timestamp');
            $table->char('account', 36);
            $table->string('type', 8);
            $table->integer('amount');
            $table->integer('balance');
            $table->string('description', 256);

            $table->primary('id');
        });

        Schema::create('transfer_fee', function (Blueprint $table) {
            $table->char('srcbank', 36);
            $table->char('dstbank', 36);
            $table->integer('amount');

            $table->primary(['srcbank', 'dstbank']);
        });

        Schema::table('account', function (Blueprint $table) {
            $table->foreign('bank')->references('id')->on('bank');
            $table->foreign('user')->references('id')->on('user');
        });

        Schema::table('transaction', function (Blueprint $table) {
            $table->foreign('account')->references('id')->on('account')->onDelete('cascade');
        });

        Schema::table('transfer_fee', function (Blueprint $table) {
            $table->foreign('srcbank')->references('id')->on('bank');
            $table->foreign('dstbank')->references('id')->on('bank');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('account')) {
            Schema::table('account', function (Blueprint $table) {
                $table->dropForeign(['bank', 'user']);
            });
        }

        if (Schema::hasTable('transaction')) {
            Schema::table('transaction', function (Blueprint $table) {
                $table->dropForeign(['account']);
            });
        }

        if (Schema::hasTable('transfer_fee')) {
            Schema::table('transfer_fee', function (Blueprint $table) {
                $table->dropForeign(['srcbank', 'dstbank']);
            });
        }

        Schema::dropIfExists('bank');
        Schema::dropIfExists('account');
        Schema::dropIfExists('user');
        Schema::dropIfExists('transaction');
        Schema::dropIfExists('transfer_fee');
    }
}
