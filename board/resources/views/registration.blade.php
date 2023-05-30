@extends('layout.layout')

@section('title', 'Registration')

@section('css')
    <link rel="stylesheet" href="{{asset('css/registration.css')}}">
@endsection

@section('contents')
    <div>Registration</div>
    @include('layout.inc.errorsvalidate')
    <form action="{{route('users.registration.post')}}" method="POST">
        @csrf
        <label for="name">Name : </label>
        <input type="text" name="name" id="name" value="{{old('name')}}">
        <br>
        <label for="email">Email : </label>
        <input type="text" name="email" id="email" value="{{old('email')}}">
        <br>
        <label for="password">password : </label>
        <input type="password" name="password" id="password">
        <br>
        <label for="passwordchk">password : </label>
        <input type="password" name="passwordchk" id="passwordchk">
        <br>
        <button type="submit">Registration</button>
        <button type="button" onclick="location.href='{{route('users.login')}}'">Cancel</button>
    </form>
@endsection

@section('js')
@endsection