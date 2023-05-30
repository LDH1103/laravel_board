@if(count($errors) > 0)
    {{-- $errors가 객체기 때문에, 그냥 반복문을 돌리진 못함 --}}
    @foreach ($errors->all() as $error)
        <div class="divError">{{$error}}</div>
    @endforeach
@endif