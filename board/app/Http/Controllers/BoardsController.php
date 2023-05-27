<?php

// + 쿼리 빌더:
// + SQL 쿼리를 프로그래밍 방식으로 작성하기 위한 도구
// + SQL 쿼리를 생성하는 데 사용되며,
// + 데이터베이스 작업을 수행하는 데 필요한 다양한 메서드를 제공
// + 개발자는 PHP 코드로 쿼리 빌더 메서드를 사용하여 데이터베이스 테이블에 대한 SELECT, INSERT, UPDATE, DELETE 등의 작업을 수행
// + 쿼리 빌더는 SQL 문법을 직접 작성하는 대신 체이닝 방식을 사용하여 쿼리를 구축할 수 있으며,
// + 이는 가독성과 유지보수성을 향상시킴
// + 쿼리 빌더는 쿼리 결과를 객체나 배열 형태로 반환할 수 있어 다양한 데이터 조작 및 가공 작업에 유용

// + ORM (Eloquent ORM):
// + 개체-관계 매핑(Object-Relational Mapping)을 제공
// + Eloquent ORM은 데이터베이스 테이블과 PHP 객체 간의 매핑을 자동화하여,
// + 객체 지향 방식으로 데이터베이스 작업을 수행할 수 있도록 함
// + 개발자는 Eloquent 모델 클래스를 정의하고,
// + 이 클래스는 테이블과 매핑되는 속성과 메서드를 포함
// + Eloquent 모델은 데이터베이스 레코드를 객체로 표현하고,
// + 개발자는 이를 사용하여 데이터베이스 작업을 수행
// + 테이블의 각 열(컬럼)을 객체의 속성으로 매핑하고, 테이블의 각 행(레코드)을 객체의 인스턴스로 매핑함
// + 매핑된 객체를 통해 개발자는 데이터베이스의 데이터를 객체로 쉽게 조작할 수 있음
// + 데이터베이스 작업을 위해 SQL 쿼리를 직접 작성하지 않아도 되며,
// + 객체 지향 프로그래밍의 장점인 코드의 가독성, 재사용성, 유지보수성을 활용할 수 있음
// + 데이터의 검색, 저장, 수정, 삭제 등 다양한 CRUD 작업을 지원하며,
// + 관계형 데이터베이스의 관계를 표현하고 관리하는 기능도 제공

// + 매핑 : 두 개체 또는 구조 사이의 상호 연결 또는 대응을 의미
// + Eloquent ORM에서 "데이터베이스 테이블과 PHP 객체 간의 매핑"은
// + 데이터베이스의 테이블과 PHP 객체(모델) 간의 상호 연결을 자동화하는 과정을 말함
// + 일반적으로 관계형 데이터 베이스에서는 테이블을 사용해서 데이터를 저장하고,
// + 각 테이블은 행과 열로 구성됨
// + 반면, 객체지향 프로그래밍에서는 클래스와 객체를 사용하여 데이터와 동작을 추상화 함
// + 객체들은 속성(데이터)과 메서드(동작)를 가지며, 코드 내에서 상호 작용하고 로직을 구현

// + 관계형 데이터베이스 :  MySQL, Oracle, ...
// + 데이터를 테이블로 구성하여 구조화된 형식으로 저장
// + 각 테이블은 행과 열로 구성되며, 각 행은 고유한 식별자를 가짐
// + 테이블 간의 관계를 통해 데이터 간의 연결 및 조작 가능
// + ACID 원칙(원자성, 일관성, 고립성, 지속성)을 준수하여 데이터 일관성과 무결성을 유지
// + SQL을 사용하여 데이터베이스 작업 수행

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
        $boards->timestamps = false; // 조회수 올려도 수정일자 바뀌지않게
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
