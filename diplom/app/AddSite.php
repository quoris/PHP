<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class AddSite extends Model
{
    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'sites';

    /**
     * Определяет необходимость отметок времени для модели.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Проверка сайта на принадлежность и добавление
     *
     * @param $sData
     */
    public function checkBelong($sData)
    {
        $aboutBelongAndAddInfo = array();
        $urlSite = $sData['url']."/seoinspector-verification273ty9ymd9521p28.html";
        $metaTage = '<meta name="seoinspector-verification" content="273ty9ymd9521p28"/>';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlSite);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)");
        $sourceCode = curl_exec($ch);
        curl_close($ch);

        if(strpos($sourceCode, "seoinspector-verification: 273ty9ymd9521p28") !== false)
        {
            $aboutBelongAndAddInfo['belong'] = true;
            $aboutBelongAndAddInfo['add'] = $this->addSite($sData);
        } else {

            $c = curl_init();
            curl_setopt($c, CURLOPT_URL, $sData['url']);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
            $sourceCodeWithMetatage = curl_exec($c);
            curl_close($c);

            if(strpos($sourceCodeWithMetatage, $metaTage) !== false)
            {
                $aboutBelongAndAddInfo['belong'] = true;
                $aboutBelongAndAddInfo['add'] = $this->addSite($sData);
            } else {
                $aboutBelongAndAddInfo['belong'] = "Сайт не прошел проверку. Пожалуйста, проверьте корректность введенных данных и повторите попытку.";
            }
        }
        return $aboutBelongAndAddInfo;
    }

    /**
     * Добавление сайта в БД
     *
     * @param $addedSite
     * @return array
     */
    public function addSite($addedSite)
    {
        $urlWithoutSpaces = trim($addedSite['url']);
        $firstDivisionUrl = explode("//", $urlWithoutSpaces);
        $secondDivisionUrl = explode("/", $firstDivisionUrl[1]);
        $domain = $secondDivisionUrl[0];

        $currentUserId = DB::table('users')->where('email', Auth::user()->email)->value('id');
        $allSitesInService = DB::table('sites')->pluck('url_site');

        $indicatorAdd = 0;

        foreach ($allSitesInService as $as)
        {
            if($as == $domain)
            {
                $indicatorAdd++;
            }
        }

        $aboutAddSite = [];
        if($indicatorAdd != 0)
        {
            $aboutAddSite['isAlreadyAdded'] = 'Сайт уже добавлен в систему. Пожалуйста, добавьте другой сайт.';

        } else {

            DB::table('sites')->insert([
                'user_id' => $currentUserId, 'url_site' => $domain, 'description_site' => $addedSite['description'], 'paid_till' => "", 'is_site_changed' => 0
            ]);

            $aboutAddSite['isAlreadyAdded'] = true;
            $aboutAddSite['urlAddedSite'] = $domain;
        }
        return $aboutAddSite;
    }
}