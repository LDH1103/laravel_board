@extends('layout.layout')

@section('title', 'API TEST')

@section('contents')
    <form id="apiForm">
        @csrf
        <label for="method">Method : </label>
        <select id="method" name="method">
            <option value="GET">GET</option>
            <option value="POST">POST</option>
            <option value="PUT">PUT</option>
            <option value="DELETE">DELETE</option>
        </select>
        <br>
        <label for="url">URL : </label>
        <input type="text" id="url" name="url" placeholder="localhost 뒤부터 입력" size=80 value="/api/list/">
        <br>
        <button type="submit">API TEST!</button>
    </form>
    <div id="apiData">
    </div>
    <div>POST 사용법 : /api/list/타이틀/컨텐츠</div>
@endsection

@section('js')
    <script src="{{asset('js/apitest.js')}}"></script>
@endsection




