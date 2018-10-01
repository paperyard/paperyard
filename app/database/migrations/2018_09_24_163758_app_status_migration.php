<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppStatusMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        //
        Schema::create('app_status', function (Blueprint $table) {
            $table->increments('app_id');
            $table->integer('app_installed')->default(0);
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
         Schema::dropIfExists('app_status');
    }
}
