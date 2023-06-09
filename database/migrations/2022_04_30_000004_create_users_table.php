<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('username')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->datetime('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('nip', 18)->nullable();
            $table->text('jabatan')->nullable();
            $table->string('ttd')->nullable();
            $table->text('digisign')->nullable();
            $table->string('remember_token')->nullable();
            $table->integer('roleaccess');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
