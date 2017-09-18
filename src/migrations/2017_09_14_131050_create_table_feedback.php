<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFeedback extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasTable('feedback')) {
            Schema::create('feedback', function (Blueprint $table) {
                $table->increments('id');
                $table->string('lang');
                $table->string('name');
                $table->string('phone');
                $table->string('email');
                $table->string('subject');
                $table->text('msg');

                $table->timestamps();
                $table->softDeletes();
                $table->enum('status', ['New', 'Reply', 'Read']);
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
        //

        Schema::dropIfExists('feedback');
    }
}
