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
            $table->string('password');
            $table->string('email')->unique();
            $table->string('verification_code')->nullable();
            $table->timestamp('validity_period')->nullable(); // + 메일인증코드 만료시간
            $table->timestamp('email_verified_at')->nullable(); // email 인증 시각
            $table->string('name');
            $table->rememberToken(); // 로그인 유지하기 기능
            $table->timestamps();
            $table->softDeletes();
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
