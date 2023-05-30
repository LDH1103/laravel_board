@extends('layout.layout')

@section('title', 'Login')

@section('css')
    <link rel="stylesheet" href="{{asset('css/view.css')}}">
@endsection

@section('contents')
    <div>Login</div>
    @include('layout.inc.errorsvalidate')
    {{-- <div>{{isset($success) ? $success : ''}}</div> --}}
    <div>{{Session::has('success') ? session('success') : ''}}</div>
    <form action="{{route('users.login.post')}}" method="POST">
        @csrf
        <label for="email">Email : </label>
        <input type="text" name="email" id="email">
        <br>
        <label for="password">password : </label>
        <input type="password" name="password" id="password">
        <br>
        <button type="submit">Login</button>
        <button type="button" onclick="location.href='{{route('users.registration')}}'">Ragistration</button>
    </form>
@endsection

@section('js')
@endsection