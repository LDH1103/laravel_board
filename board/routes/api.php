<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiListController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/list/{id}', [ApiListController::class, 'getlist']);
// Route::post('/list', [ApiListController::class, 'postlist']); 
// Route::put('/list/{id}', [ApiListController::class, 'putlist']); 
// Route::delete('/list/{id}', [ApiListController::class, 'deletelist']); 

// + 사용자가 직접 URL입력하는거 막기
// + 1. app/Http/Middleware/PreventDirectAccess.php 생성
// + 2. app/Http/Kernel.php 파일의 $routeMiddleware 배열안에 미들웨어 추가
// +        'prevent.direct.access' => \App\Http\Middleware\PreventDirectAccess::class,
// + 3. 라우터 그룹을 사용해서 미들웨어 적용
Route::group(['middleware' => 'prevent.direct.access'], function () {
    Route::get('/list/{id}', [ApiListController::class, 'getlist']);
    // Route::post('/list', [ApiListController::class, 'postlist']);
    Route::post('/list/{title}/{content}', [ApiListController::class, 'postlist']); // + api test페이지를 위해 사용
    Route::put('/list/{id}', [ApiListController::class, 'putlist']);
    Route::delete('/list/{id}', [ApiListController::class, 'deletelist']);
});

// + API 테스트를 위해 추가해줌
Route::get('/test', [ApiListController::class, 'getapitest']);
Route::post('/test', [ApiController::class, 'postList']);