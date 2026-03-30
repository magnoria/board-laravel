<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function signup(Request $request){
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // 평문저장시 문제가 될수 있기에 암호화
        ]);
        return response()->json($user);
    }

    // public function login(Request $request){
    //     $user = User::where('email', $request->email)->first();

    //     if(!$user){
    //         return response()->json([
    //             'message' => '사용자 없음'
    //         ], 404);
    //     }
        
    //     if(!Hash::check($request->password, $user->password)){ // 입력값, db값
    //         return response()->json([
    //             'message' => '비밀번호 틀림'
    //         ], 401);
    //     }
    //     return response()->json([
    //         'message' => '로그인성공',
    //         'user'=> $user
    //     ]);
    // }  확인용 코드
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'message' => '이메일 또는 비밀번호가 올바르지 않습니다.'
            ], 401);
        }

        return response()->json([
            'message' => '로그인 성공',
            'token' => $token,
            'user' => auth()->user(),
        ]);
    }
    
}
