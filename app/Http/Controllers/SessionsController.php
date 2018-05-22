<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['only' => ['create']]);
    }
    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
       $credentials = $this->validate($request, [
        'email' => 'required|email|max:255',
        'password' => 'required'
       ]);
       if(Auth::attempt($credentials, $request->has('remember'))) {
            if (Auth::User->activated)
                {
                     return redirect()->intended(route('users.show', [Auth::user()]))->with('success', '欢迎回来');
                 } else {
                    Auth::logout();
                    return redirect('/')->with('warning', '账号还未激活，请检查邮箱');
                 }
       }else {
            return redirect()->back()->with('danger', '很抱歉，邮箱和密码不匹配');
       }
       return;
    }

    public function destroy()
    {
        Auth::logout();
        return redirect('login')->with('success','退出成功');
    }

}
