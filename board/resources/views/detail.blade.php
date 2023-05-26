<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail</title>
</head>
<body>
    <button type="button" onclick="location.href='{{route('boards.index')}}'">리스트 페이지로</button>
    <button type="button" onclick="location.href='{{route('boards.edit', ['board' => $data->id])}}'">수정 페이지로</button>
    <button type="button" onclick="location.href='{{route('boards.destroy', ['board' => $data->id])}}'">삭제하기</button>
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
</body>
</html>