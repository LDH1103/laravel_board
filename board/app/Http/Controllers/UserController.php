<?php
/**************************************************
 * 프로젝트명   : laravel_board
 * 디렉토리     : Controllers
 * 파일명       : UserController.php
 * 이력         :   v001 0530 DH.Lee new
**************************************************/

namespace App\Http\Controllers;

use App\Mail\Common;
use App\Mail\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login() {

        // 로그 남기기
        // $arr['key'] = 'test';
        // $arr['kim'] = 'park';
        // Log::emergency('emergency', $arr);
        // Log::alert('alert', $arr);
        // Log::critical('critical', $arr);
        // Log::error('error', $arr);
        // Log::warning('warning', $arr);
        // Log::notice('notice', $arr);
        // Log::info('info', $arr);
        // Log::debug('debug', $arr);
        // Log::debug('debug');
        $ch = curl_init();
        // $url = 'http://openapi.airport.co.kr/service/rest/AirportCodeList/getAirportCodeList'; /*URL*/
        $url = 'http://openapi.airport.co.kr/service/rest/FlightStatusList/getFlightStatusList'; /*URL*/
        $url = 'http://openapi.airport.co.kr/service/rest/FlightScheduleList/getDflightScheduleList';
        $queryParams = '?' . urlencode('serviceKey') . '=q1Huc9EjZjvBYP%2BNKi0ILB%2FS%2BhmYkimR2o%2FIfQey1bl0NGsyoDHQJVnSYSEwPfvS9C9SqZkaD%2FXMw9SLRkLlqA%3D%3D'; /*Service Key*/

        curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $response = curl_exec($ch);
        $result = simplexml_load_string($response);
        $a = json_encode($result, JSON_UNESCAPED_UNICODE);

        curl_close($ch);

        return print_r($a);
        return view('login');
    }

    public function loginpost(Request $req) {
        Log::debug('로그인 시작');
        // 유효성 검사
        $req->validate([
            'email'    => 'required|email|max:100'
            ,'password' => 'required|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);

        // 유저 정보 습득
        $user = User::where('email', $req->email)->first();

        if (!$user) {
            $error = '아이디와 비밀번호를 확인해 주세요.';
            return redirect()->back()->with('error', $error);
        }

        if(!$user || !(Hash::check($req->password, $user->password))) {
            $error = '아이디와 비밀번호를 확인해 주세요.';
            $errorArr = [
                '이메일' => $req->email
                ,'비밀번호'   => Hash::make($req->password)
                ,'db비밀번호' => $user->password
            ];
            Log::debug('유효성 실패', $errorArr);
            return redirect()->back()->with('error', $error);
        }
        Log::debug('유효성 ok');

        if(!$user->email_verified_at) {
            $error = '이메일이 인증되지 않았습니다.';
            // 이메일 인증 재발송 버튼을 클릭하면 이메일 재전송 로직을 처리하는 메서드로 이동하도록 리다이렉트
            $resendEmailUrl = route('resend.email', ['email' => $user->email]);
            return redirect()->back()->with('error', $error)->with('resend_email', true)->with('resend_email_url', $resendEmailUrl);
            // return redirect()->back()->with('error', $error);
        }

        // 유저 인증작업
        Auth::login($user);
        // Auth::check() : 인증작업 성공여부
        if(Auth::check()) {
            session($user->only('id')); // 세션에 인증된 회원 pk 등록
            // session($user->only('id', 'name')); // 세션으로 이름출력하기 test
            // + intended() : 사용자가 로그인하기 전에 접근하려고 했던 URL로 사용자를 리다이렉트
            // + intended(route('boards.index')) : 사용자가 로그인하기 전에 게시판의 인덱스 페이지로 접근하려고 했었다면, 로그인 후에는 해당 페이지로 자동으로 리다이렉트함
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

        // Mail::to($user)->send(new SendEmail($user));
        
        // 회원가입 완료 후 로그인 페이지로 이동
        // return redirect()
        //     ->route('users.login')
        //     ->with('success', '회원가입을 완료 했습니다.<br>가입하신 아이디와 비밀번호로 로그인 해 주십시오.');

        // + 회원가입시 이메일 인증 test
        $verification_code = Str::random(30); // 인증 코드 생성
        $validity_period = now()->addMinutes(30); // 유효기간 설정

        $user->verification_code = $verification_code;
        $user->validity_period = $validity_period;
        $user->save();

        Mail::to($user->email)->send(new SendEmail($user));

        return redirect()->route('users.login')->with('success', '회원가입을 완료 했습니다.<br>이메일을 확인하여 계정을 활성화해 주세요.<br>인증 유효기간은 30분입니다.');

    }

    // + 이메일 인증 test
    public function verify($code, $email) {
        $user = User::where('verification_code', $code)->where('email', $email)->first();

        if (!$user) {
            $error = '유효하지 않은 이메일 주소입니다.';
            return redirect()->route('users.login')->with('error', $error);
        }

        $currentTime = now();
        $validityPeriod = $user->validity_period;

        if ($currentTime > $validityPeriod) {
            $error = '인증 유효시간이 만료되었습니다.';
            $resendEmailUrl = route('resend.email', ['email' => $user->email]);
            return redirect()->back()->with('error', $error)->with('resend_email', true)->with('resend_email_url', $resendEmailUrl);
        }

        $user->verification_code = null;
        $user->validity_period = null;
        $user->email_verified_at = now();
        $user->save();

        $success = '이메일 인증이 완료되었습니다.<br>가입하신 아이디와 비밀번호로 로그인 해 주십시오.';
        return redirect()->route('users.login')->with('success', $success);
    }

    // + 이메일 인증 재전송 TEST
    public function resend_email(Request $req) {
        $user = User::where('email', $req->email)->first();

        if (!$user) {
            $error = '해당 이메일로 가입된 계정이 없습니다.';
            return redirect()->back()->with('error', $error);
        }

        if ($user->email_verified_at) {
            $error = '해당 계정은 이미 이메일 인증이 완료되었습니다.';
            return redirect()->back()->with('error', $error);
        }

        $verification_code = Str::random(30);
        $validity_period = now()->addMinutes(1);

        $user->verification_code = $verification_code;
        $user->validity_period = $validity_period;
        $user->save();

        Mail::to($user->email)->send(new SendEmail($user));

        $success = '이메일 인증 메일을 재전송하였습니다.<br>이메일을 확인하여 계정을 활성화해 주세요.';
        return redirect()->back()->with('success', $success);
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
