<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Account extends Model
{
    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Определяет необходимость отметок времени для модели.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Проверка измененных данных и запись в бд
     *
     * @param $updData
     */
    public function updateUserData($updData)
    {
        $infoAboutAdd = [];
        $currentUser = DB::table('users')->where('id', Auth::user()->id)->first();

        if($updData['name'] != "" && $updData['name'] != $currentUser->name)
        {
            DB::table('users')->where('id', Auth::user()->id)->update(['name' => $updData['name'] ]);
            Auth::user()->name = $updData['name'];
            array_push($infoAboutAdd, "Имя успешно изменено!");
        }

        if($updData['email'] != "" && $updData['email'] != $currentUser->email)
        {
            DB::table('users')->where('id', Auth::user()->id)->update(['email' => $updData['email'] ]);
            Auth::user()->email = $updData['email'];
            array_push($infoAboutAdd, "Email успешно изменен!");
        }

        if($updData['password'] != "" && !Hash::check($updData['password'], $currentUser->password))
        {
            $newPass = Hash::make($updData['password']);
            DB::table('users')->where('id', Auth::user()->id)->update(['password' => $newPass]);
            array_push($infoAboutAdd, "Пароль успешно изменен!");
        }

        return $infoAboutAdd;
    }
}
