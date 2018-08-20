<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NotificationsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('notif_id');

            $table->integer('notif_user_id');
            $table->string('notif_title');
            $table->string('notif_keywords');

            $table->boolean('tax_relevant')->default(0);
            $table->string('tags')->nullable();
            $table->boolean('send_email')->default(0);

            $table->string('se_subject')->nullable();
            $table->string('se_receiver_name')->nullable();
            $table->string('se_receiver_email')->nullable();
            $table->longText('se_message')->nullable();

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
        Schema::dropIfExists('notifications');
    }
}
