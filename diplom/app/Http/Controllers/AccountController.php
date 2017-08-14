<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class AccountController extends Controller
{
    /**
     * Страница должна быть показана только авторизованным пользователям
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Вывод данных пользователя в личном кабинете
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userData = DB::table('users')->where('id', Auth::user()->id)->first();
        return view('account', ['userData' => $userData]);
    }

    /**
     * Получение измененных данных с личного кабинета
     *
     * @param  Request $request
     * @return Response
     */
    public function changeUserData()
    {
        $updatedData = array(
            'name' => Input::get("name"),
            'email' => Input::get("email"),
            'password' => Input::get('password')
        );

        $accountModel = new Account();
        $messageAboutChanges = $accountModel->updateUserData($updatedData);

        return redirect('account')->with('messageAboutChanges', $messageAboutChanges);
    }
}
