{{-- <div>
    test
    {{$user}}
</div> --}}
<!DOCTYPE html>
<html>
<head>
    <title>이메일 인증</title>
</head>
<body>
    <h1>이메일 인증</h1>
    <div>안녕하세요, {{ $name }}님</div>
    <div>아래 링크를 클릭하여 이메일을 인증해 주세요</div>
    <br>
    <div><a href="{{ route('users.verify', ['code' => $verification_code, 'email' => $email]) }}">이메일 인증 링크</a></div>
    <br>
    <div>이 인증 링크는 {{ $validityPeriod }}까지 유효합니다.</div>
</body>
</html>
