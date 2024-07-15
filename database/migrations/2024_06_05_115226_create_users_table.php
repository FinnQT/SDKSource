<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('username')->unique();
            $table->string('protect_code');
            $table->string('email');
            $table->string('password');
            $table->string('name')->nullable();
            $table->text('location')->nullable();
            $table->string('CCCD')->nullable();
            $table->decimal('balance', 13, 2)->default(0);
            $table->integer('status');
            $table->integer('link');
            $table->integer('is_admin')->default(0);
            $table->string('ip_address');
            $table->string('last_login_ip');
            $table->text('log_change_inf')->nullable();
            $table->text('log_protect_code')->nullable();
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
};
