<?php

namespace App\Http\Middleware;

use Closure;

class PreventDirectAccess
{
    // + Closure class :
    // + PHP에서 익명 함수(클로저)를 표현하는 데 사용
    // + 클로저는 콜백이나 함수 매개변수로 사용되며, 나중에 실행할 수 있는 작은 코드 블록을 정의하는 데 사용됨
    // + Laravel 미들웨어에서 Closure class는 다음 미들웨어나 라우트 핸들러를 나타내는 데 사용함
    public function handle($request, Closure $next)
    {
        // + 요청이 Ajax || JSON일때만 허용
        // + wantsJson() : 현재 요청이 JSON 응답을 원하는지 여부
        if ($request->ajax() || $request->wantsJson()) {
            // + $next : 미들웨어 스택의 다음 단계를 나타냄
            // + $next($request) : 현재 미들웨어가 실행을 완료하고 다음 미들웨어로 요청을 전달하는 역할
            // + 익명 함수를 $next에 지정하는 이유는 미들웨어 스택에서 다음 단계로 요청을 전달하기 위해서
            // + 현재 미들웨어가 실행을 완료하고 다음 미들웨어로 제어를 넘길 수 있음
            // + 미들웨어는 일련의 단계로 구성되며, 각 단계는 요청을 처리하고 다음 단계로 넘기는 역할을 함
            // + $next($request)를 호출하여 다음 미들웨어로 요청을 전달함으로써 처리 흐름이 지속되고, 다음 단계에서 추가적인 처리를 수행할 수 있음
            return $next($request);
        }

        // + abort() :
        // + 예외를 발생시키고 HTTP 응답을 반환하는 데 사용됨
        // + 첫 번째 매개변수는 HTTP 응답 코드, 두 번째 매개변수는 에러 메세지를 지정할 수 있음
        // + ex) abort(403)는 403 Forbidden 응답을 발생시키고 해당 응답을 클라이언트에게 반환함
        // + 조건을 확인하고 특정 조건이 충족되지 않을 때 응답을 반환하는 데 사용됨
        abort(403, '직접 접근 차단');
    }
}
