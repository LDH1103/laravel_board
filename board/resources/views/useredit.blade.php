@extends('layout.layout')

@section('title', 'User edit')

@section('css')
    <link rel="stylesheet" href="{{asset('css/useredit.css')}}">
@endsection

@section('contents')
<div class="Container">
    @include('layout.inc.errorsvalidate')
    <form action="{{route('users.edit.post')}}" method="POST">
    @csrf
        <label for="email">email : </label>
        <input type="text" id="email" name="email" value="{{Auth::user()->email}}" disabled>
        <br>
        <label for="name">name : </label>
        <input type="text" id="name" name="name" value="{{Auth::user()->name}}">
        <br>
        <label for="password">password : </label>
        <input type="password" id="password" name="password">
        <br>
        <label for="passwordchk">password check : </label>
        <input type="password" name="passwordchk" id="passwordchk">
        <br>
        <button type="submit">수정하기</button>
    </form>
</div>
@endsection

@section('js')
@endsection