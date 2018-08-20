<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DocumentPagesMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('document_pages', function (Blueprint $table) {
            $table->increments('doc_page_id');
            $table->integer('doc_id');
            $table->integer('doc_page_num');
            $table->string('doc_page_image_preview')->nullable();
            $table->string('doc_page_thumbnail_preview')->nullable();
            $table->longText('doc_page_text')->nullable();
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
          Schema::dropIfExists('document_pages');
    }
}
