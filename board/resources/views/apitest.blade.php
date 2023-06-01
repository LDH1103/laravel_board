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
    <br>
    <div id="apiData">
    </div>
    <br>
    <div>GET : /api/list/글 번호</div>
    <div>POST : /api/list/타이틀/컨텐츠</div>
    <div>PUT : /api/list/글번호?title=제목&content=내용</div>
    <div>DELETE : /api/list/글번호</div>
@endsection

@section('js')
    <script src="{{asset('js/apitest.js')}}"></script>
@endsection




