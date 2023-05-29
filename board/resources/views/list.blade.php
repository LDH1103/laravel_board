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
{{-- 페이징 --}}
<div class="listPageButton">
    {{-- laravel 페이징 페이지 버튼 출력 : {{$data->links()}} --}}
    {{-- App \ Providers \ AppServiceProvider 에서

    use Illuminate\Pagination\Paginator;
    public function boot()
    {
        Paginator::useBootstrap();
    }

    해줘야 부트스트랩과 호환됨
    페이징 커스텀하기 :
    @if ($data->onFirstPage())
        <a href="{{ $data->previousPageUrl() }}" rel="prev" class="pageHidden">이전</a>
    @else
        <a href="{{ $data->previousPageUrl() }}" rel="prev">이전</a>
    @endif

    @if ($data->currentPage() > 1)
        <a href="{{ $data->previousPageUrl() }}"></a>
    @endif
    @for($i = 1; $i <= $data->lastPage(); $i++)
        <a href="{{$data->url($i)}}">{{$i}}</a>
    @endfor
    @if ($data->currentPage() < $data->lastPage())
        <a href="{{$data->nextPageUrl()}}"></a>
    @endif

    @if ($data->hasMorePages())
        <a href="{{ $data->nextPageUrl() }}" rel="next">다음</a>
    @else
        <a href="{{ $data->nextPageUrl() }}" rel="next" class="pageHidden">다음</a>
    @endif

    currentPage(): 현재 페이지의 번호를 가져옴
    hasPages(): 페이지가 있는지 여부를 확인
    lastPage(): 마지막 페이지의 번호를 가져옴
    nextPageUrl(): 다음 페이지의 URL을 가져옴
    onFirstPage(): 현재 페이지가 첫 번째 페이지인지 여부를 확인
    perPage(): 페이지당 아이템 수를 가져옴
    previousPageUrl(): 이전 페이지의 URL을 가져옴
    total(): 전체 아이템 수를 가져옴
    url($page): 지정된 페이지 번호($page)의 URL을 가져옴
    items(): 현재 페이지에 표시할 아이템 컬렉션을 가져옴
    count(): 현재 페이지에 표시된 아이템 수를 가져옴
    firstItem(): 현재 페이지의 첫 번째 아이템의 인덱스를 가져옴
    lastItem(): 현재 페이지의 마지막 아이템의 인덱스를 가져옴

    좀더 세분화 해서 작업하기 : 
        1. php artisan vendor:publish --tag=laravel-pagination 명령어로 views폴더 안에 vendor/pagination 폴더를 생성
        2. resources/views/vendor/pagination/custom.blade.php 파일을 생성
        3. 리스트 뷰파일에서 {{ $data->links('vendor.pagination.custom') }}
--}}
    {{ $data->links('vendor.pagination.custom') }}

</div>
@endsection

@section('js')

@endsection