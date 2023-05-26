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
        // 패키지 관리자 설치 : composer require doctrine/dbal

        // 기존 테이블 alter
        // hits 컬럼에 기본값을 넣어주기 위함 : php artisan migrate
        Schema::table('boards', function (Blueprint $table) {
            $table->integer('hits')->default(0)->change();
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
    }
};
