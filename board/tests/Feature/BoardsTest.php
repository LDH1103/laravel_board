<?php

namespace Tests\Feature;

use App\Models\Boards;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoardsTest extends TestCase
{
    // test 만들기 : php artisan make:test BoardsTest
    // 이름의 끝은 항상 Test로 끝나게 해야함

    use RefreshDatabase; // 테스트 완료후 DB 초기화를 위한 트레이트(class 안에서 사용하는 객체)
    use DatabaseMigrations; // DB 마이그레이션

    /**
     * A basic feature test example.
     *
     * @return void
     */
    // 메소드명은 항상 test로 시작해야함
    // + '/boards' URL에 GET 요청을 보낸 후, 해당 응답이 /users/login으로 리다이렉트되는지 확인하는 테스트
    public function test_index_게스트_리다이렉트()
    {
        // + '/boards' URL에 GET 요청을 보내는 코드
        $response = $this->get('/boards');

        // + 응답이 /users/login으로 리다이렉트되는지 확인하는 코드
        $response->assertRedirect('/users/login');
    }

    public function test_index_유저인증() {
        // 테스트용 유저 생성
        $user = new User([
            'email' => 'aa@aa.aa'
            ,'name' => '테스트'
            ,'password' => 'asdsad'
        ]);
        $user->save();

        // + 위에서 생성한 사용자로 인증된 상태에서 '/boards' URL에 GET 요청을 보냅
        $response = $this->actingAs($user)->get('/boards');

        // + 위에서 생성한 사용자로 인증되었는지 확인
        $this->assertAuthenticatedAs($user);
    }

    public function test_index_유저인증_뷰반환() {
        // 테스트용 유저 생성
        $user = new User([
            'email' => 'aa@aa.aa'
            ,'name' => '테스트'
            ,'password' => 'asdsad'
        ]);
        $user->save();

        // + 위에서 생성한 사용자로 인증된 상태에서 '/boards' URL에 GET 요청을 보냅
        $response = $this->actingAs($user)->get('/boards');

        // + '/boards' URL에 GET 요청 후, 응답의 뷰(view)가 'list'인지 확인하는 테스트
        $response->assertViewIs('list');
    }

    // + 사용자 인증 및 게시물 데이터 확인 관련 테스트를 수행
    public function test_index_유저인증_뷰반환_데이터확인() {
        // 테스트용 유저 생성
        $user = new User([
            'email' => 'aa@aa.aa'
            ,'name' => '테스트'
            ,'password' => 'asdsad'
        ]);
        $user->save();

        $board1 = new Boards([
            'title'     => 'test1'
            ,'content'  => 'content1'
        ]);
        $board1->save();

        $board2 = new Boards([
            'title'     => 'test2'
            ,'content'  => 'content2'
        ]);
        $board2->save();

        // + 위에서 생성한 사용자로 인증된 상태에서 '/boards' URL에 GET 요청을 보냅
        $response = $this->actingAs($user)->get('/boards');

        // + 응답의 뷰에서 'data'라는 변수를 가지고 있는지 확인
        $response->assertViewHas('data');
        // + 응답의 내용에 'test1'과 'test2'라는 텍스트가 있는지 확인(list 페이지에서는 content는 보이지않고 title만 보이기때문)
        $response->assertSee('test1');
        $response->assertSee('test2');
    }
}
