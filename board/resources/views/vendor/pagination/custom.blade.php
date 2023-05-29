{{-- @if ($paginator->onFirstPage())
    <a href="{{ $paginator->url(1) }}" rel="prev" class="pageHidden">처음</a>
@else
    <a href="{{ $paginator->previousPageUrl(1) }}" rel="prev">처음</a>
@endif

@if ($paginator->onFirstPage())
    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pageHidden">이전</a>
@else
    <a href="{{ $paginator->previousPageUrl() }}" rel="prev">이전</a>
@endif

{{-- range(1, $paginator->lastPage())는 1부터 $paginator->lastPage()까지의 숫자 범위를 생성하고,
    각 숫자를 $i 변수에 할당 --}}
{{-- @foreach(range(1, $paginator->lastPage()) as $i)
    <a href="{{$paginator->url($i)}}">{{$i}}</a>
@endforeach

@if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" rel="next">다음</a>
    @else
    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pageHidden">다음</a>
    @endif
    
    @if ($paginator->hasMorePages())
    <a href="{{ $paginator->url($paginator->lastPage()) }}" rel="next">마지막</a>
    @else
    <a href="{{ $paginator->url($paginator->lastPage()) }}" rel="next" class="pageHidden">마지막</a>
    @endif  --}}

{{-- ---------------블록 페이징----------------- --}}
@php
    // 블록으로 묶을(한번에 보여줄) 페이지 수
    $block = 5;

    // 현재 페이지를 중심으로 좌측에 표시할 첫 번째 페이지
    // max() : 계산 결과가 1보다 작을 경우 1로 설정하여 음수나 0이 되는 것을 방지
    // floor($block / 2) : $block의 절반 값을 내림하여 현재 페이지를 중심으로 시작 페이지(가장 왼쪽에 표시될 페이지)를 설정
    // ex) $block = 5일떄 floor($block / 2) = 2, 현재페이지가 7페이지일때 7 - 2 = 5, 시작 페이지는 5페이지
    $startPage = max(1, $paginator->currentPage() - floor($block / 2));

    // $startPage를 기준으로 우측에 표시할 마지막 페이지를 계산
    // min(..., $paginator->lastPage()) : 계산 결과가 전체 페이지 수($paginator->lastPage())보다 큰 경우 전체 페이지 수로 설정
    // startPage + $block - 1는 $startPage부터 $block의 개수만큼 페이지를 표시하므로 마지막 페이지(가장 오른쪽에 표시될 페이지)를 설정
    // ex) $startPage = 5, $block = 5일떄 마지막페이지는 9페이지
    $endPage = min($startPage + $block - 1, $paginator->lastPage());
@endphp

{{-- 첫 페이지 버튼 --}}
@if ($paginator->onFirstPage())
    <a href="{{ $paginator->url(1) }}" rel="prev" class="pageHidden">처음</a>
@else
    <a href="{{ $paginator->url(1) }}" rel="prev">처음</a>
@endif

{{-- 이전 블럭 버튼 --}}
@php
    // 최소 1페이지, 현재페이지 - $block
    $previousBlockPage = max(1, $paginator->currentPage() - $block);
@endphp
@if ($paginator->onFirstPage())
    <a href="{{ $paginator->url($previousBlockPage) }}" rel="prev" class="pageHidden">이전</a>
@else
    <a href="{{ $paginator->url($previousBlockPage) }}" rel="prev">이전</a>
@endif

{{-- 페이징 --}}
{{-- range() : 지정된 범위의 숫자를 생성하여 배열로 반환 --}}
@foreach(range($startPage, $endPage) as $i)
    <a href="{{$paginator->url($i)}}">{{$i}}</a>
@endforeach

{{-- 다음 블럭 버튼 --}}
@php
    // 최대 마지막 페이지, 현재 페이지 + $block
    $nextBlockPage = min($paginator->lastPage(), $paginator->currentPage() + $block);
@endphp
@if ($paginator->hasMorePages())
    <a href="{{ $paginator->url($nextBlockPage) }}" rel="next">다음</a>
@else
    <a href="{{ $paginator->url($nextBlockPage) }}" rel="next" class="pageHidden">다음</a>
@endif

{{-- 마지막 페이지 --}}
@if ($paginator->hasMorePages())
    <a href="{{ $paginator->url($paginator->lastPage()) }}" rel="next">마지막</a>
@else
    <a href="{{ $paginator->url($paginator->lastPage()) }}" rel="next" class="pageHidden">마지막</a>
@endif
