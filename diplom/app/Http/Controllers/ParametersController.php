<?php

namespace App\Http\Controllers;

use App\Parameter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ParametersController extends Controller
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
     * Вывод значений отслеживаемых SEO-параметров
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $par = new Parameter();
        $isPossToShow = $par->isPossibleToShowParameters($id);

        if(!empty($isPossToShow))
        {
            return view('parameters', ['page' => $isPossToShow['currentPage'], 'changeParametersInfo' => $isPossToShow['parameters']]);
        } else {
            return view('parameters', ['showParametersError' => 'Вы не можете ознакомиться с состоянием SEO-параметров данной страницы, т.к. ее нет в списке страниц, принадлежащих вашим сайтам.']);
        }
    }

    /**
     * Редирект на страницу со списком страниц сайта
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function goToPages()
    {
        if(isset($_POST['goToPages']))
        {
            $sId = DB::table('pages')->where('url_page', Input::get('currentPageParameters'))->value('site_id');
            $sName = DB::table('sites')->where('id', $sId)->value('url_site');
            return redirect('sitePages/'.$sName);
        }
    }
}
