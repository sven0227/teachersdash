<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('role')->default('user');
            $table->string('name', 190);
            $table->string('company', 190)->nullable();
            $table->string('email', 190)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 190);
            $table->string('user_company_email', 190)->nullable();
            $table->string('user_company_phone', 190)->nullable();
            $table->string('user_company_website', 190)->nullable();
            $table->integer('package_id')->nullable();
            $table->dateTime('package_ends_at')->nullable();
            $table->text('settings')->nullable();
            $table->boolean('sms_status')->default(0);
            $table->double('sms_balance')->default(0);
            $table->boolean('is_show_about_us_form')->default(1);
            $table->boolean('is_show_contact_us_form')->default(1);
            $table->rememberToken();
            $table->integer('login_nums')->default(0);
            $table->timestamps();
        });

    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
