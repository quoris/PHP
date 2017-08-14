<?php

namespace App\Http\Controllers;

use App\AddSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class AddSiteController extends Controller
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
     * Возвращает представление addSite
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('addSite');
    }

    /**
     * Отправка данных сайта для проверки и добавления в сервис
     *
     * @param  Request $request
     * @return Response
     */
    public function getSiteData()
    {
        $siteData = array(
            'url' => Input::get('url'),
            'description' => Input::get('description')
        );

        $addSite = new AddSite();
        $messAboutBelongAndAdd = $addSite->checkBelong($siteData);

        if($messAboutBelongAndAdd['belong'] !== true)
        {
            return view('addSite', ['belongError' => $messAboutBelongAndAdd['belong']]);
        }

        if($messAboutBelongAndAdd['add']['isAlreadyAdded'] !== true)
        {
            return view('addSite', ['addError' => $messAboutBelongAndAdd['add']['isAlreadyAdded']]);
        }

        if($messAboutBelongAndAdd['belong'] === true && $messAboutBelongAndAdd['add']['isAlreadyAdded'] === true)
        {
            return redirect('addPages/'.$messAboutBelongAndAdd['add']['urlAddedSite']);
        }
    }
}
