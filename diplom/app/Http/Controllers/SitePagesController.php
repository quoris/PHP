<?php

namespace App\Http\Controllers;

use App\SitePages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class SitePagesController extends Controller
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
     * Возвращает представление sitePages
     *
     * @return \Illuminate\Http\Response
     */
    public function index($url)
    {
        $sp = new SitePages();
        $isPossToShowSite = $sp->isPossibleToShowSite($url);

        if(!empty($isPossToShowSite))
        {
            return view('sitePages', ['sn' => $isPossToShowSite['sn'], 'pt' => $isPossToShowSite['pt'], 'sp' => $isPossToShowSite['sp']]);
        } else {
            return view('sitePages', ['showSiteError' => 'Вы не можете просматривать данные этого сайта, т.к. вы не добавляли его в систему.']);
        }
    }

    /**
     * Обработка возможных действий на странице со списком страниц
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function reditectToAddPageOrDeletePage()
    {
        if(isset($_POST['goToSites']))
        {
            return redirect('home');
        }

        if(isset($_POST['addPages']))
        {
            $siteNamePageWhichNeedToAdd = Input::get('siteNamePageWhichNeedToAdd');
            return redirect('addPages/'.$siteNamePageWhichNeedToAdd);
        }

        if(isset($_POST['deletePage']))
        {
            DB::table('values')->where('page_id', Input::get('deletePage'))->delete();
            DB::table('pages')->where('id', Input::get('deletePage'))->delete();
            return redirect()->back();
        }
    }
}
