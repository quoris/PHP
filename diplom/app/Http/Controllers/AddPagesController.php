<?php

namespace App\Http\Controllers;

use App\AddPages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class AddPagesController extends Controller
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
     * Проверка возможности добавления страниц выбранного сайта
     *
     * @return \Illuminate\Http\Response
     */
    public function index($url)
    {
        $addPages = new AddPages();
        $isPossToAdd = $addPages->isPossibleToAddPages($url);

        if($isPossToAdd != 0)
        {
            return view('addPages', ['siteUrl' => $url]);
        } else {
            return view('addPages', ['addPagesReceivedUrlError' => 'Вы не можете добавить страницы для этого сайта, т.к. вы не добавили его в систему']);
        }
    }

    /**
     * Получение введенных урлов
     *
     * @param  Request $request
     * @return Response
     */
    public function getUrls()
    {
        $pagesAndDomain = Input::all();

        $ap = new AddPages();
        $answerAboutPages = $ap->addPages($pagesAndDomain);

        if(!empty($answerAboutPages['countUrl']))
        {
            return redirect()->back()->with('countUrlError', $answerAboutPages['countUrl']);
        }

        if(!empty($answerAboutPages['wrongUrl']))
        {
            return redirect()->back()->with('wrongUrlError', $answerAboutPages['wrongUrl']);
        }

        if(!empty($answerAboutPages['alreadyIs']))
        {
            return redirect()->back()->with('alreadyIsError', $answerAboutPages['alreadyIs']);
        }

        if(empty($answerAboutPages['countUrl']) && empty($answerAboutPages['alreadyIs']) && empty($answerAboutPages['wrongUrl']))
        {
            return redirect('sitePages/'.$pagesAndDomain['sitePagesWhichAdded']);
        }
    }
}