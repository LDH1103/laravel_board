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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class UserController extends Controller
{
    public function login() {
        return view('login');
    }

    public function loginpost(Request $req) {
        // 유효성 검사
        $req->validate([
            'email'    => 'required|email|max:100'
            ,'password' => 'required|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);

        // 유저 정보 습득
        $user = User::where('email', $req->email)->first();
        if(!$user || !(Hash::check($req->password, $user->password))) {
            $error = '아이디와 비밀번호를 확인해 주세요.';
            return redirect()->back()->with('error', $error);
        }

        // 유저 인증작업
        Auth::login($user);
        // Auth::check() : 인증작업 성공여부
        if(Auth::check()) {
            session($user->only('id')); // 세션에 인증된 회원 pk 등록
            return redirect()->intended(route('boards.index'));
        } else {
            $error = '인증작업 에러';
            return redirect()->back()->with('error', $error);
        }
    }

    public function registration() {
        return view('registration');
    }

    public function registrationpost(Request $req) {
        // 유효성 검사
        $req->validate([
            'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ,'email'    => 'required|email|max:100'
            ,'password' => 'required_with:passwordchk|same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/' // required_with:passwordchk|same:passwordchk : 비밀번호와 비밀번호 확인을 비교함
        ]);

        $data['name'] = $req->name;
        $data['email'] = $req->email;
        $data['password'] = Hash::make($req->password); // Hash::make : 해쉬화(암호화)

        $user = User::create($data); // insert후 결과가 $user에 담김
        if(!$user) {
            $error = '시스템 에러가 발생하여, 회원 가입에 실패했습니다.<br>잠시 후에 다시 시도해 주십시오.';
            return redirect()
                ->route('users.registration')
                ->with('error', $error);
        }

        // 회원가입 완료 후 로그인 페이지로 이동
        return redirect()
            ->route('users.login')
            ->with('success', '회원가입을 완료 했습니다.<br>가입하신 아이디와 비밀번호로 로그인 해 주십시오.');
    }

    public function logout() {
        Session::flush(); // 세션 파기
        Auth::logout(); // 로그아웃
        return redirect()->route('users.login');
    }

    public function withdraw() {
        $id = session('id');
        $result = User::destroy($id);
        Session::flush(); // 세션 파기
        Auth::logout(); // 로그아웃
        // return var_dump($result);
        // ---------------- 에러처리 추가하기(laravel 에러 핸들링) ----------------
        return redirect()->route('users.login');
    }

    public function edit() {
        if(auth()->guest()) {
            return redirect()->route('users.login');
        }
        return view('useredit');
    }

    public function editpost(Request $req) {
        // var_dump($req);
        // return 'aaa';

        if($req->name) {
            $req->validate([
                'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ]);
        }

        if($req->password) {
            $req->validate([
                'password' => 'required_with:passwordchk|same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
            ]);
        }

        $samePW = Hash::check($req['password'], Auth::user()->password);
        if ($samePW) {
            return redirect()->back()->with('error', '사용중인 비밀번호와 같습니다.');
        }

        $user = User::find(Auth::user()->id);
        if($req->name) {
            $user['name'] = $req->name;
        }

        if($req->password) {
            $user['password'] = Hash::make($req->password);
        }
        $user->save();

        return redirect()->route('users.edit');
    }
}
