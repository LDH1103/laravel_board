<?php
/**************************************************
 * 프로젝트명   : laravel_board
 * 디렉토리     : Controllers
 * 파일명       : UserController.php
 * 이력         :   v001 0530 DH.Lee new
**************************************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    function login() {
        return view('login');
    }

    function registration() {
        return view('registration');
    }

    function registrationpost(Request $req) {
        // 유효성 검사
        $req->validate([
            'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ,'email'    => 'required|email|max:100'
            ,'password' => 'required_unless:password,passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/' // required_unless : 뒤 두개의 값이 같은지 비교함
        ]);

        $data['name'] = $req->name;
        $data['email'] = $req->email;
        $data['password'] = Hash::make($req->password); // Hash::make : 해쉬화(암호화)

        $user = User::create($data); // insert후 결과가 $user에 담김
        if(!$user) {
            $errors[] = '시스템 에러가 발생하여, 회원 가입에 실패했습니다.';
            $errors[] = '잠시 후에 다시 시도해 주십시오.';
            return redirect()
                ->route('users.registration')
                ->with('errors', collect($errors));
        }

        // 회원가입 완료 후 로그인 페이지로 이동
        return redirect()
            ->route('users.login')
            ->with('success', '회원가입을 완료 했습니다.<br>가입하신 아이디와 비밀번호로 로그인 해 주십시오.');
    }
}
