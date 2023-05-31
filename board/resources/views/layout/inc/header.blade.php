<div class="divHeader">
    <a href="{{route('boards.index')}}" class="aHeader">BOARD</a>
</div>
<div class="userButton">
    {{-- 로그인 된 상태 --}}
    @auth
        <div>{{Auth::user()->name."님, 환영합니다."}}</div>
        <a href="{{route('users.logout')}}">로그아웃</a>
        <a href="{{route('users.edit')}}">정보수정</a>
    @endauth

    {{-- 로그인 안된 상태 --}}
    @guest
        <a href="{{route('users.login')}}">로그인</a>
    @endguest
</div>