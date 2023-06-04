@extends('layout.layout')

@section('title', 'Mail TEST')

@section('contents')
    <form action="{{route('mails.mail.post')}}" method="POST">
        @csrf
        <label for="mailAddress">email : </label>
        <input type="text" id="mailAddress" name="mailAddress">
        <button type="submit">submit</button>
    </form>
@endsection

@section('js')
@endsection




