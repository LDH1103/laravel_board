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
            // session($user->only(['id', 'name'])); // 세션으로 이름출력하기 test
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
        // var_dump(Auth::user()->password);
        // return 'aaa';

        // if($req->name && $req->password) {
        //     $req->validate([
        //         'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
        //         ,'password' => 'required_with:passwordchk|same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        //         ,'currentPw' => 'regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        //     ]);
        // } else if($req->name) {
        //     $req->validate([
        //         'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
        //         ,'currentPw' => 'regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        //     ]);
        // } else if($req->password) {
        //     $req->validate([
        //         'password' => 'required_with:passwordchk|same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        //         ,'currentPw' => 'regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        //     ]);
        // }

        // $currentPw = Hash::check($req->currentPw, Auth::user()->password);
        // if(!$currentPw) {
        //     return redirect()->back()->with('error', '현재 비밀번호가 일치하지 않습니다.');
        // }

        // $samePW = Hash::check($req['password'], Auth::user()->password);
        // if($samePW) {
        //     return redirect()->back()->with('error', '사용중인 비밀번호와 같습니다.');
        // }

        // $user = User::find(Auth::user()->id);
        // // $user = User::find(session('id'));

        // if($req->name) {
        //     $user['name'] = $req->name;
        // }
        
        // if($req->password) {
        //     $user['password'] = Hash::make($req->password);
        // }
        
        // $user->save();

        // 다른 ver-------------------------------------------
        // 수정할 항목을 배열에 담는 변수
        $arrKey = [];

        // 기존 데이터 획득
        $baseuser = User::find(Auth::user()->id);

        // 기존 패스워드 체크
        if(!Hash::check($req->currentPw, Auth::user()->password)) {
            return redirect()->back()->with('error', '현재 비밀번호가 일치하지 않습니다.');
        }

        if(Hash::check($req->password, Auth::user()->password)) {
            return redirect()->back()->with('error', '사용중인 비밀번호와 같습니다.');
        }

        // 수정할 항목을 배열에 담는 처리
        if($req->name !== $baseuser->name) {
            $arrKey[] = 'name';
        }

        if(isset($req->password)) {
            $arrKey[] = 'password';
        }

        // 유효성 체크를 하는 모든 항목 리스트
        $chkList = [
            'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ,'password' => 'required_with:passwordchk|same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
            ,'currentPw' => 'regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ];
        $arrchk = [];

        // 유효성 체크할 항목 세팅
        $arrchk['currentPw'] = $chkList['currentPw'];
        foreach($arrKey as $val) {
            $arrchk[$val] = $chkList[$val];
        }

        // 유효셩 체크
        $req->validate($arrchk);

        // 수정할 데이터 셋팅
        foreach($arrKey as $val) {
            if($val === 'password') {
                $baseuser->$val = Hash::make($req->password);
                continue; // 반복문이 돌때 continue를 만나면, 그 다음 반복으로 넘어감($val === 'password'일 경우, 바로밑의 $baseuser->$val = $req->$val;는 실행안됨)
            }
            $baseuser->$val = $req->$val;
        }
        $baseuser->save(); // update
        
        // ---------------------------------------------------

        return redirect()->route('users.edit');
    }
}
