<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddPages extends Model
{
    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * Проверка возможности добавления страниц выбранному сайту
     *
     * @param $sData
     */
    public function isPossibleToAddPages($url)
    {
        $sitesCurrUser = DB::table('sites')->where('user_id', Auth::user()->id)->pluck('url_site');

        $isUrlBelongUsersSite = 0;
        foreach ($sitesCurrUser as $scu)
        {
            if($scu == $url)
            {
                $isUrlBelongUsersSite++;
            }
        }

        return $isUrlBelongUsersSite;
    }

    /**
     * Проверка введенных урлов и их добавление
     *
     * @param $sData
     */
    public function addPages($pagesWithDomain)
    {
        $notCleanedArrUrls = explode("\n", $pagesWithDomain['pages']);

        $arrUrls = [];
        foreach ($notCleanedArrUrls as $v)
        {
            if(stristr($v, 'http') !== false)
            {
                $withoutSpaces = trim($v);
                array_push($arrUrls, $withoutSpaces);
            }
        }

        $arrErrors = array();
        $arrErrors['countUrl'] = "";
        $arrErrors['wrongUrl'] = "";
        $arrErrors['alreadyIs'] = "";


        // Проверка ввода превышенного количества страниц


        if(count($arrUrls) > 50)
        {
            $arrErrors['countUrl'] = "Превышено максимальное количество добавляемых страниц.";
        }


        // Проверка ввода страниц, не принадлежащих сайту


        foreach ($arrUrls as $au)
        {
            if(stristr($au, $pagesWithDomain['sitePagesWhichAdded']) === false)
            {
                $arrErrors['wrongUrl'] = "Добавлять нужно только страницы, принадлежащие добавленному сайту";
            }
        }


        // Проверка добавления уже добавленных страниц


        $siteIdInWhichAddPages = DB::table('sites')->where('url_site', $pagesWithDomain['sitePagesWhichAdded'])->value('id');
        $allPagesCurrentSite = DB::table('pages')->where('site_id', $siteIdInWhichAddPages)->pluck('url_page');

        $alreadyAddesUrls = [];
        for($i = 0; $i < count($arrUrls); $i++)
        {
            for($j = 0; $j < count($allPagesCurrentSite); $j++)
            {
                if($arrUrls[$i] === $allPagesCurrentSite[$j])
                 {
                    array_push($alreadyAddesUrls, $arrUrls[$i]);
                 }
            }
        }
        $arrErrors['alreadyIs'] = $alreadyAddesUrls;

        if(empty($arrErrors['countUrl']) && empty($arrErrors['wrongUrl']) && empty($arrErrors['alreadyIs']))
        {
            $idSiteAddedPages = DB::table('sites')->where('url_site', $pagesWithDomain['sitePagesWhichAdded'])->value('id');

            foreach ($arrUrls as $a)
            {
                $idAddedPage = DB::table('pages')->insertGetId(['site_id' => $idSiteAddedPages, 'url_page' => $a, 'is_page_changed' => 0]);
                $IdsAllParameters = DB::table('parameters')->pluck('id');

                foreach ($IdsAllParameters as $iap)
                {
                    DB::table('values')->insert(['page_id' => $idAddedPage, 'parameter_id' => $iap, 'status' => '', 'value' => 0, 'time' => '']);
                }

            }
        }
        return $arrErrors;
    }
}