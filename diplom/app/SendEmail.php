<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendEmail extends Model
{
    /**
     * Получение данных об изменившихся SEO-параметрах страниц сайтов, добавленных юзеров
     * и их организация для отправки на email
     */
    public function getSitesAndUsersData()
    {
        $pageIds = DB::table('values')->where('status', 'Изменен')->pluck('page_id', 'id')->unique();
        $arrChangedPagesAndParameters = [];

        foreach ($pageIds as $pi)
        {
            $changedParametersIds = DB::table('values')->where('status', 'Изменен')->where('page_id', $pi)->pluck('parameter_id');

            $tmpArrSiteInfo = [];
            array_push($tmpArrSiteInfo, $pi);

            foreach ($changedParametersIds as $cpi)
            {
                array_push($tmpArrSiteInfo, $cpi);
            }
            array_push($arrChangedPagesAndParameters, $tmpArrSiteInfo);
        }

        $sites = [];    // тут информация об изменившихся сайтах, их страницах и параметрах
        $siteIds = [];  // вспомогательный массив

        foreach ($arrChangedPagesAndParameters as $acpp)
        {
            $siteId = DB::table('pages')->where('id', $acpp[0])->value('site_id');

            if(!isset($sites[$siteId]))
            {
                $sites[$siteId] = [
                    'pages' => []
                ];

                $siteIds[] = $siteId;
            }
            $sites[$siteId]['pages'][] = $acpp;
        }

        $sitesInfo = [];
        foreach ($siteIds as $si)
        {
            $infoAboutChangedSite = DB::table('sites')->where('id', $si)->get();
            array_push($sitesInfo, $infoAboutChangedSite);
        }

        foreach ($sitesInfo as $siteInfo)
        {
            foreach ($siteInfo as $info)
            {
                $info = (array) $info;
                $sites[$info['id']] = array_merge($sites[$info['id']], $info);
            }
        }

        // Сопоставление данных пользователя с его сайтами. Массив пользователей изменившихся сайтов

        $usersInfo = [];

        foreach ($sites as $v)
        {
            foreach ($v as $key => $value)
            {
                if($key == "user_id")
                {
                    if(count($usersInfo) == 0)
                    {
                        $userData = DB::table('users')->where('id', $value)->get();

                        foreach ($userData as $ud)
                        {
                            $tmpArrUserInfo = [];
                            $tmpArrUserInfo['id'] = $ud->id;
                            $tmpArrUserInfo['name'] = $ud->name;
                            $tmpArrUserInfo['email'] = $ud->email;
                            array_push($usersInfo, $tmpArrUserInfo);
                        }
                    } else {
                        foreach ($usersInfo as $ui)
                        {
                            if($ui['id'] != $value)
                            {
                                $userData = DB::table('users')->where('id', $value)->get();

                                foreach ($userData as $ud)
                                {
                                    $tmpArrUserInfo = [];
                                    $tmpArrUserInfo['id'] = $ud->id;
                                    $tmpArrUserInfo['name'] = $ud->name;
                                    $tmpArrUserInfo['email'] = $ud->email;
                                    array_push($usersInfo, $tmpArrUserInfo);
                                }
                            }
                        }
                    }
                }
            }
        }

        // Получение урлов страниц и названий измененных параметров

        foreach ($usersInfo as $a)
        {
            foreach ($sites as &$subArr)
            {
                foreach ($subArr as $saKey => $saValue)
                {
                    if($saKey == "user_id" && $saValue == $a['id'])
                    {
                        foreach ($subArr['pages'] as &$subSubArr)
                        {
                            $subSubArr[0] = DB::table('pages')->where('id', $subSubArr[0])->value('url_page');

                            for ($i = 1; $i < count($subSubArr); $i++)
                            {
                                $subSubArr[$i] = DB::table('parameters')->where('id', $subSubArr[$i])->value('parameter');
                            }
                        }
                        unset($subSubArr);
                    }
                }
            }
            unset($subArr);
        }

        // Объединение измененных сайтов, принадлежащих пользователю. Объединение пользователей с их сайтами

        $arrAllChangedSites = [];
        $arrAllUnitedInfoAboutUsersAndSites = [];   // каждый элемент массива - это имя и почта пользователя, а также изменившиеся данные всех его сайтов

        foreach ($usersInfo as $ui)
        {
            foreach ($sites as $si)
            {
                foreach ($si as $sk => $sv)
                {
                    if($sk == "user_id" && $sv == $ui['id'])
                    {
                        array_push($arrAllChangedSites, $si);
                    }
                }
            }
            $currentUserAndSitesInfo['name'] = $ui['name'];
            $currentUserAndSitesInfo['email'] = $ui['email'];
            $currentUserAndSitesInfo['emailData'] = $arrAllChangedSites;

            $arrAllChangedSites = [];

            array_push($arrAllUnitedInfoAboutUsersAndSites, $currentUserAndSitesInfo);
        }

        if(!empty($arrAllUnitedInfoAboutUsersAndSites))
        {
           $this->sendEmails($arrAllUnitedInfoAboutUsersAndSites);
        }
    }

    /**
     * Отправка данных на email'ы пользователей
     *
     * @param $changedSitesAndUsersInfo
     */
    public function sendEmails($changedSitesAndUsersInfo)
    {
        foreach ($changedSitesAndUsersInfo as $csui)
        {
            Mail::send('emails.aboutChanges', ['infoAboutChangingParameters' => $csui['emailData']], function ($message) use ($csui)
            {
                $message->from('support@seoinspector.com', 'SEO Inspector');
                $message->to($csui['email'], $csui['name'])->subject('Изменились SEO-параметры ваших сайтов!');
            });
        }
    }
}