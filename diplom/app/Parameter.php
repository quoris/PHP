<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class Parameter extends Model
{
    /**
     * Возвращает список отслеживаемых SEO-параметров с их значениями
     *
     * @param $id
     * @return array
     */
    public function isPossibleToShowParameters($id)
    {
        $idsAllSitesCurrUser = DB::table('sites')->where('user_id', Auth::user()->id)->pluck('id');
        $arrAllPagesAllSitesCurrUser = [];

        foreach ($idsAllSitesCurrUser as $iascu)
        {
            $idsAllPagesCurrUsersSite = DB::table('pages')->where('site_id', $iascu)->pluck('id');
            foreach ($idsAllPagesCurrUsersSite as $iapcus)
            {
                array_push($arrAllPagesAllSitesCurrUser, $iapcus);
            }
        }

        $isPageBelongCurrUsersSite = 0;
        foreach ($arrAllPagesAllSitesCurrUser as $apascu)
        {
            if($apascu == $id)
            {
                $isPageBelongCurrUsersSite++;
            }
        }

        $parametersAndPageInfo = [];
        if($isPageBelongCurrUsersSite != 0)
        {
            $currentPage = DB::table('pages')->where('id', $id)->value('url_page');
            $parameters = DB::table('parameters')->pluck('parameter');
            $status = DB::table('values')->where('page_id', $id)->pluck('status');
            $values = DB::table('values')->where('page_id', $id)->pluck('value');
            $time = DB::table('values')->where('page_id', $id)->pluck('time');

            $changeParametersInfo = [];

            for($i = 0; $i < count($parameters); $i++)
            {
                $p = array();
                $p['parameter'] = $parameters[$i];
                $p['status'] = $status[$i];

                if($i == 0)
                    $p['value'] = $values[$i];

                if($values[$i] == 0)
                    $p['value'] = "Отсутствует";

                if($values[$i] == 1)
                    $p['value'] = "Присутствует";

                if($values[$i] != 0 && $values[$i] != 1 && $i != (0 && 12 && 14))
                    $p['value'] = $values[$i]." символов";

                if($parameters[$i] == "alternate" || $parameters[$i] == "canonical" || $parameters[$i] == "nofollow или <meta name=\"robots\" content=\"nofollow\">")
                {
                    if($values[$i] == 0)
                        $p['value'] = "Отсутствует";
                    if($values[$i] == 1)
                        $p['value'] = $values[$i]." атрибут";
                    if($values[$i] > 1 && $values[$i] < 5)
                        $p['value'] = $values[$i]." атрибута";
                    if($values[$i] > 4)
                        $p['value'] = $values[$i]." атрибутов";
                }

                if($parameters[$i] == "<noindex> или <meta name=\"robots\" content=\"noindex\">")
                {
                    if($values[$i] == 0)
                        $p['value'] = "Отсутствует";
                    if($values[$i] == 1)
                        $p['value'] = $values[$i]." секция";
                    if($values[$i] > 1 && $values[$i] < 5)
                        $p['value'] = $values[$i]." секции";
                    if($values[$i] > 4)
                        $p['value'] = $values[$i]." секций";
                }

                if($parameters[$i] == "Разметка Open Graph")
                {
                    if($values[$i] == 0)
                        $p['value'] = "Отсутствует";
                    if($values[$i] == 1)
                        $p['value'] = $values[$i]." тег";
                    if($values[$i] > 1 && $values[$i] < 5)
                        $p['value'] = $values[$i]." тега";
                    if($values[$i] > 4)
                        $p['value'] = $values[$i]." тегов";
                }

                $p['time'] = $time[$i];
                array_push($changeParametersInfo, $p);
            }

            $parametersAndPageInfo['currentPage'] = $currentPage;
            $parametersAndPageInfo['parameters'] = $changeParametersInfo;
        }

        return $parametersAndPageInfo;
    }
}