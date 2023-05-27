@extends('layout.layout')

@section('css')
<link rel="stylesheet" href="{{asset('css/list.css')}}">
@endsection

@section('contents')
<div class="divInsertBtn">
    <a href="{{route('boards.create')}}" class="insertBtn">작성하기</a>
</div>

<div class="container text-center contentsBorder">
    <div class="row listInfo">
        <div class="col">글 번호</div>
        <div class="col">글 제목</div>
        <div class="col">조회수</div>
        <div class="col">등록일</div>
        <div class="col">수정일</div>
    </div>
    <hr>
    @forelse($data as $item)
    <div class="row">
        <div class="col">{{$item->id}}</div>
        <div class="col"><a href="{{route('boards.show', ['board' => $item->id])}}">{{$item->title}}</a></div>
        <div class="col">{{$item->hits}}</div>
        <div class="col">{{$item->created_at}}</div>
        <div class="col">{{$item->updated_at}}</div>
    </div>
    @empty
    <div class="row">
        <div class="col"></div>
        <div class="col">게시글 없음</div>
        <div class="col"></div>
        <div class="col"></div>
        <div class="col"></div>
    </div>
    @endforelse
</div>
@endsection

@section('js')

@endsection