<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SitePages extends Model
{
    /**
     * Возвращает страницы выбранного сайта
     *
     * @param $url
     * @return array
     */
    public function isPossibleToShowSite($url)
    {
        $allSitesCurrUser = DB::table('sites')->where('user_id', Auth::user()->id)->pluck('url_site');

        $isUrlUsersSite = 0;
        foreach ($allSitesCurrUser as $ascu)
        {
            if($ascu == $url)
            {
                $isUrlUsersSite++;
            }
        }

        $sp = [];
        $currSiteInfo = [];
        if($isUrlUsersSite != 0)
        {
            $currSiteInfo['sn'] = $url;
            $sid = DB::table('sites')->where('url_site', $url)->value('id');
            $currSiteInfo['pt'] = DB::table('sites')->where('url_site', $url)->value('paid_till');

            $idsPagesCurrSite = DB::table('pages')->where('site_id', $sid)->pluck('id');

            foreach ($idsPagesCurrSite as $ipcs)
            {
                $tmpArr = [];
                $tmpArr['pageId'] = $ipcs;
                $tmpArr['pageUrl'] = DB::table('pages')->where('id', $ipcs)->value('url_page');
                $tmpArr['indicator'] = DB::table('pages')->where('id', $ipcs)->value('is_page_changed');

                array_push($sp, $tmpArr);
            }
            $currSiteInfo['sp'] = $sp;
        }

        return $currSiteInfo;
    }
}