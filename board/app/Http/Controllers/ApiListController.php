<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;

class ApiListController extends Controller
{
    // + $id를 사용하여 Boards 모델에서 해당 게시물을 찾고, JSON 형식으로 응답함
    public function getlist($id) {
        $user = Boards::find($id);
        return response()->json($user, 200);
    }

    public function postlist(Request $req) {
        // 유효성 체크 필요

        $boards = new Boards([
            'title'     => $req->title
            ,'content'  => $req->content
        ]);
        $boards->save();

        $arr['errorcode'] = '0';
        $arr['msg'] = 'success';
        $arr['data'] = $boards->only('id', 'title');

        // return response()->json($boards, 200);
        return $arr; // laravel이 자동으로 json형식으로 바꿔줌
    }

    public function putlist(Request $req, $id) {
        // 유효성 체크 필요

        $boards = Boards::find($id);
        if (!$boards) {
            return response()->json(['error' => '게시글을 찾을 수 없습니다.'], 404);
        }

        $boards->title = $req->title;
        $boards->content = $req->content;
        $boards->save();

        $arr['errorcode'] = '0';
        $arr['msg'] = 'success';
        $arr['data'] = $boards->only('id', 'title', 'content', 'updated_at');

        return $arr;
    }

    public function deletelist($id) {
        $boards = Boards::find($id);
        if (!$boards) {
            return response()->json(['error' => '게시글을 찾을 수 없습니다.'], 404);
        }

        $boards->delete();

        $arr['errorcode'] = '0';
        $arr['msg'] = 'success';

        return $arr;
    }

    // + API 테스트를위해 만듦
    public function getapitest() {
        return view('apitest');
    }

}
