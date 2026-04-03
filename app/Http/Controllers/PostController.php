<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Post;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class PostController extends Controller
{   
    //생성
    public function store(Request $request){

        $user = JWTAuth::parseToken()->authenticate();

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $user->id
        ]);

         return response()->json($post);
    }
//전체 목록
    public function index(){
        $posts = Post::with('user')->get();

        return response()->json($posts);
    }
// 개별 목록
    public function show($id)
{
    $post = Post::with('user')->findOrFail($id);

    return response()->json($post);
}
//수정 //이부분 수정할것 2026-03-30
    public function update(Request $request, $id){
        $user = JWTAuth::parseToken()->authenticate();

        $post = Post::findOrFail($id);

        //id 확인 추가 04-01
        if($post->user_id !== $user->id){
            return response()->json([
                'message' => '권한없음'
            ], 403);
        }

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);
        return response()->json($post);
    }
//삭제
public function destroy($id)
{
    $user = JWTAuth::parseToken()->authenticate();
    $post = Post::findOrFail($id);

      //id 확인 추가 04-01
        if($post->user_id !== $user->id){
            return response()->json([
                'message' => '권한없음'
            ], 403);
        }

    $post->delete();

    return response()->json([
        'message' => '삭제 완료'
    ]);
}
}
