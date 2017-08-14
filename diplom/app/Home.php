<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Home extends Model
{
    /**
     * Возвращает данные всех сайтов пользователя
     *
     * @return array
     */
    public function getAllUserSites()
    {
        $addedSiteCurrUser = array();

        $curUsId = DB::table('users')->where('email', Auth::user()->email)->value('id');
        $infoAboutAddesSitesCurrUser = DB::table('sites')->where('user_id', $curUsId)->get();

        foreach ($infoAboutAddesSitesCurrUser as $i) {
            $tmpArr = array();
            $tmpArr['site_id'] = $i->id;
            $tmpArr['url'] = $i->url_site;
            $tmpArr['description'] = $i->description_site;

            $pagesCurrSite = DB::table('pages')->where('site_id', $i->id)->get();
            $countPagesCurrSite = count($pagesCurrSite);
            $tmpArr['countPages'] = $countPagesCurrSite;
            $tmpArr['indicator'] = DB::table('sites')->where('id', $i->id)->value('is_site_changed');

            array_push($addedSiteCurrUser, $tmpArr);
        }

        return $addedSiteCurrUser;
    }
}