<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddressBookMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('address_book', function (Blueprint $table) {
            $table->increments('ab_id');
            $table->integer('ab_user_id');
            $table->boolean('ab_possible_recipient')->default(0);
            $table->string('ab_parent_id')->nullable();
            $table->string('ab_shortname')->nullable();
            $table->string('ab_salutation')->nullable();
            $table->string('ab_firstname')->nullable();
            $table->string('ab_lastname')->nullable();
            $table->string('ab_company')->nullable();
            $table->string('ab_address_line1')->nullable();
            $table->string('ab_address_line2')->nullable();
            $table->string('ab_zipcode')->nullable();
            $table->string('ab_town')->nullable();
            $table->string('ab_country')->nullable();
            $table->string('ab_telephone')->nullable();
            $table->string('ab_email')->nullable();
            $table->string('ab_notes')->nullable();
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
        Schema::dropIfExists('address_book');
    }
}
