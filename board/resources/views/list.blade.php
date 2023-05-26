<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
</head>
<body>
    {{-- php artisan route:list에서 확인 가능 --}}
    <a href="{{route('boards.create')}}">작성하기</a>
    <table>
        <tr>
            <th>글 번호</th>
            <th>글 제목</th>
            <th>조회수</th>
            <th>등록일</th>
            <th>수정일</th>
        </tr>
        @forelse($data as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td><a href="{{route('boards.show', ['board' => $item->id])}}">{{$item->title}}</a></td>
                <td>{{$item->hits}}</td>
                <td>{{$item->created_at}}</td>
                <td>{{$item->updated_at}}</td>
            </tr>
        @empty
            <tr>
                <td></td>
                <td>게시글 없음</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforelse
    </table>
</body>
</html>
