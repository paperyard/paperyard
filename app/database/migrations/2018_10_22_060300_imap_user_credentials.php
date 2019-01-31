<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImapUserCredentials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('imap_credentials', function (Blueprint $table) {
            $table->increments('imap_id');
            $table->integer('imap_user_id');
            $table->string('imap_host')->nullable;
            $table->integer('imap_port')->default(993)->nullable;
            $table->string('imap_encryption')->default('ssl')->nullable;
            $table->boolean('imap_validate_cert')->default(0);
            $table->string('imap_username')->nullable();
            $table->string('imap_password', 1000)->nullable();  
            $table->dateTime('last_run')->nullable();
            $table->boolean('valid_credential')->default(1);
            $table->string('imap_process_status')->default('idle')->nullable();  

            $table->longText('imap_folders')->nullable;

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
        Schema::dropIfExists('imap_credentials');
    }
}
