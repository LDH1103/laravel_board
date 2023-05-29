@extends('layout.layout')

@section('title', '상세 페이지')

@section('css')
<link rel="stylesheet" href="{{asset('css/datail.css')}}">
@endsection

@section('contents')
    <button type="button" onclick="location.href='{{route('boards.index')}}'">리스트 페이지로</button>
    <button type="button" onclick="location.href='{{route('boards.edit', ['board' => $data->id])}}'">수정 페이지로</button>
    <form action="{{route('boards.destroy', ['board' => $data->id])}}" method="post">
        @csrf
        @method('delete')
        <button type="submit">삭제하기</button>
    </form>
    <div>
        글 번호 : {{$data->id}}
        <br>
        제목 : {{$data->title}}
        <br>
        내용 : {{$data->content}}
        <br>
        등록일자 : {{$data->created_at}}
        <br>
        수정일자 : {{$data->updated_at}}
        <br>
        조회수 : {{$data->hits}}
    </div>
@endsection

@section('js')
@endsection