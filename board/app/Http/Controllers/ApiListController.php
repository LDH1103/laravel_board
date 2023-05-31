<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;

class ApiListController extends Controller
{
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
}
