<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class ApiListController extends Controller
{
    // + $id를 사용하여 Boards 모델에서 해당 게시물을 찾고, JSON 형식으로 응답함
    public function getlist($id) {
        $user = Boards::find($id);
    
        if(!$user) {
            $arrData['code'] = 'E01';
            $arrData['msg'] = 'Not Found';
            $arrData['errmsg'] = 'The requested page does not exist';

            return $arrData;
        }

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
        $arrDataa = [
            'code'      => '0'
            ,'msg'      => ''
            ,'errmsg'   => []
        ];

        $data = $req->only('title', 'content');
        $data['id'] = $id;
        
        $validator = Validator::make($data, [
            'id'        => 'required|integer|exists:boards' // exists : DB에 질의하기 떄문에, 주의해서 사용
            ,'title'    => 'required|between:3,30'
            ,'content'  => 'required|max:2000'
        ]);
        
        if($validator->fails()) {
            $arrData['code'] = 'E01';
            $arrData['msg'] = 'Validator Error';
            $arrData['errmsg'] = $validator->errors()->all();

            // return response()
            //     ->json(['errors' => $validator->messages()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $boards = Boards::find($id);
            $boards->title = $req->title;
            $boards->content = $req->content;
            $boards->save();

            $arrData['code'] = '0';
            $arrData['msg'] = 'success';
            $arrData['data'] = $boards->only('id', 'title', 'content', 'updated_at');
        }

        return $arrData;
    }

    public function deletelist($id) {
        $arrData = [
            'code'      => '0'
            ,'msg'      => ''
            ,'errmsg'   => []
        ];

        $data['id'] = $id;

        $validator = Validator::make($data, [
            'id'        => 'required|integer|exists:boards'
        ]);
        
        if($validator->fails()) {
            $arrData['code'] = 'E01';
            $arrData['msg'] = 'Validator Error';
            $arrData['errmsg'] = $validator->errors()->all();

            // return response()
            //     ->json(['errors' => $validator->messages()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $boards = Boards::find($id);
            
            if($boards) {
                $boards->delete();
                $arrData['code'] = '0';
                $arrData['msg'] = 'Success';
            } else {
                $arrData['code'] = 'E02';
                $arrData['msg'] = 'Already Deleted';
            }
        }
        
        return $arrData;
    }
    
    // + API 테스트를위해 만듦
    public function getapitest() {
        return view('apitest');
    }

}
