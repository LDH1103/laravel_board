<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Post;

// 모델을 사용
use App\Models\Boards;

class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $result = Boards::all();
        $result = Boards::select(['id', 'title', 'hits', 'created_at', 'updated_at'])->orderBy('hits', 'DESC')->get();
        return view('list')->with('data', $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('write');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        // 새로 생성해야 하는 데이터기 때문에(insert), 새로운 객체를 생성함(new Boards)
        $boards = new Boards([
            'title'     => $req->input('title')
            ,'content'  => $req->input('content')
        ]);
        $boards->save();
        return redirect('/boards');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $boards = Boards::find($id);
        $boards->hits++; // 조회수 올려주기
        $boards->save();

        // find() : 에외발생시 false만 리턴, 프로그램이 계속 실행됨
        // findOrFail() : 예외발생시 에러처리(404)
        return view('detail')->with('data', Boards::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $boards = Boards::find($id);
        return view('edit')->with('data', $boards);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $boards = Boards::find($id);
        $boards->title = $req->title;
        $boards->content = $req->content;
        $boards->save();

        // ----------------------

        // Boards::where('id', $id)->update([
        //     'title'     => $req->title
        //     ,'content'  => $req->content
        // ]);
        
        // 둘중 하나 사용
        // return redirect('/boards/'.$id);
        return redirect()->route('boards.show', ['board' => $id]);

        // redirect()를 사용해야 할 때 : view가 없을때(update) / 요청받은 URL과 돌려줘야하는 URL이 다를떄
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $boards = Boards::find($id);
        // $boards->deleted_at = now();
        // $boards->save();

        // ----------------------

        // Boards::where('id', $id)->update([
        //     'deleted_at'     => now()
        // ]);

        // ----------------------
        
        // Boards::find($id)->delete();

        // ---------------------- 
        // destory()와 delete()의 차이: destory()는 파라미터를 PK(id)를 받아야함,
        
        Boards::destroy($id);

        return redirect('/boards');
    }
}
