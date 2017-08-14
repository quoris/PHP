<?php

namespace App\Http\Controllers;

use App\Home;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class HomeController extends Controller
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
     * Возвращает представление home
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $home = new Home();
        $usSites = $home->getAllUserSites();
        return view('home', ['userSites' => $usSites]);
    }

    /**
     * Обработчик действий на странице со списком сайтов
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function actionWithSite()
    {
        if(isset($_POST['deleteSite']))
        {
            $idsPages = DB::table('pages')->where('site_id', Input::get('siteId'))->pluck('id');
            foreach ($idsPages as $idp)
            {
                DB::table('values')->where('page_id', $idp)->delete();
            }
            DB::table('pages')->where('site_id', Input::get('siteId'))->delete();
            DB::table('sites')->where('id', Input::get('siteId'))->delete();
            return redirect()->back();
        }

        if(isset($_POST['showSitePages']))
        {
            $siteId = Input::get('siteId');
            $siteName = DB::table('sites')->where('id', $siteId)->value('url_site');
            return redirect('sitePages/'.$siteName);
        }
    }
}
