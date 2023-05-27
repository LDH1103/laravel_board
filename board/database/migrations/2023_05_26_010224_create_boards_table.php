<?php

// + 마이그레이션은 데이터베이스 스키마를 관리하기 위한 도구
// + 마이그레이션을 사용하면 데이터베이스 테이블을 생성, 수정 또는 삭제하는 등의 스키마 변경 작업을 프로그래밍적으로 수행할 수 있음
// + Laravel의 마이그레이션은 데이터베이스의 테이블 구조를 버전 관리하고,
// + 팀원들과의 협업을 용이하게 하며, 프로젝트 배포 및 롤백 등을 관리하는 데 도움을 줌

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
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('title', 30);
            $table->string('content', 2000);
            $table->integer('hits')->default(0);
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
        Schema::dropIfExists('boards');
    }
};
