<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WebdavCredentialsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webdav_credentials', function (Blueprint $table) {
            $table->increments('webdav_id');
            $table->integer('webdav_user_id')->nullable;

            $table->string('webdav_baseuri')->nullable;
            $table->string('webdav_username')->nullable();
            $table->string('webdav_password', 1000)->nullable();  

            $table->string('webdav_pathprefix')->nullable();
    
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
        Schema::dropIfExists('webdav_credentials');
    }
}
