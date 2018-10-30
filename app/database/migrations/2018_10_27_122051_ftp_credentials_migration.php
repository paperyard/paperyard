<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FtpCredentialsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ftp_credentials', function (Blueprint $table) {
            $table->increments('ftp_id');
            $table->integer('ftp_user_id')->nullable;

            $table->string('ftp_host')->nullable;
            $table->string('ftp_username')->nullable();
            $table->string('ftp_password', 1000)->nullable();  
            $table->integer('ftp_port')->nullable;

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
        Schema::dropIfExists('ftp_credentials');
    }
}
