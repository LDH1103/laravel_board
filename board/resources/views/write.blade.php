<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write</title>
</head>
<body>
    @if(count($errors) > 0)
        {{-- $errors가 객체기 때문에, 그냥 반복문을 돌리진 못함 --}}
        @foreach ($errors->all() as $error)
            <div class="divError" style="color: red">{{$error}}</div>
        @endforeach
    @endif
    <form action="{{route('boards.store')}}" method="POST">
        @csrf
        <label for="title">제목 : </label>
        <input type="text" name="title" id="title" value="{{old('title')}}"> {{-- {{old()}} : 유효성 검사 error 발생시 기존값 유지 --}}
        <br>
        <label for="content">내용 : </label>
        <textarea name="content" id="content" cols="30" rows="10">{{old('content')}}</textarea>
        <button type="submit">작성</button>
    </form>
</body>
</html>