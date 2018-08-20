<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DocumentsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         //
         Schema::create('documents', function (Blueprint $table) {
            $table->increments('doc_id');
            $table->integer('doc_folder_id')->nullable();
            $table->integer('doc_user_id');
            $table->string('doc_org');
            $table->string('doc_prc');
            $table->string('doc_ocr');
            $table->integer('t_process');
            $table->string('process_status');
            $table->boolean('shared');
            $table->boolean('approved')->default(0);
            $table->boolean('is_archive')->default(0);
            $table->string('sender')->nullable();
            $table->string('receiver')->nullable();
            $table->string('date')->nullable();
            $table->string('tags')->nullable();
            $table->string('category')->nullable();
            $table->string('memory')->nullable();
            $table->string('tax_relevant')->nullable();
            $table->string('note')->nullable();

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
         Schema::dropIfExists('documents');
    }
}
