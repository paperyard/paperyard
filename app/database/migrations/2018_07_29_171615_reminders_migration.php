<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemindersMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('reminders', function (Blueprint $table) {
            $table->increments('reminder_id');
            $table->string('reminder_status')->default('standby');
            $table->integer('reminder_user_id');
            $table->integer('reminder_document_id')->nullable();
            $table->string('reminder_title');
            $table->longText('reminder_message');
            $table->dateTime('reminder_schedule')->nullable();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('reminders');
    }
}
