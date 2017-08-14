<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckAffilation extends Command
{
    /**
     * Название консольной команды
     *
     * @var string
     */
    protected $signature = 'checkAffilation';

    /**
     * Описание консольной команды
     *
     * @var string
     */
    protected $description = 'check websites affiliation';

    /**
     * Конструктор
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Логика проверки принадлежности сайта
     *
     * @return mixed
     */
    public function handle()
    {
        $sites = DB::table('sites')->pluck('url_site');

        foreach ($sites as $s)
        {
            $urlSite = $s."/seoinspector-verification273ty9ymd9521p28.html";
            $metaTage = '<meta name="seoinspector-verification" content="273ty9ymd9521p28"/>';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urlSite);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)");
            $sourceCode = curl_exec($ch);
            curl_close($ch);

            $c = curl_init();
            curl_setopt($c, CURLOPT_URL, $s);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)");
            $sourceCodeWithMetatage = curl_exec($c);
            curl_close($c);

            if(strpos($sourceCode, "seoinspector-verification: 273ty9ymd9521p28") === false && strpos($sourceCodeWithMetatage, $metaTage) === false)
            {
                $idDeletedSite = DB::table('sites')->where('url_site', $s)->value('id');

                $idsPages = DB::table('pages')->where('site_id', $idDeletedSite)->pluck('id');
                foreach ($idsPages as $idp)
                {
                    DB::table('values')->where('page_id', $idp)->delete();
                }
                DB::table('pages')->where('site_id', $idDeletedSite)->delete();
                DB::table('sites')->where('id', $idDeletedSite)->delete();
            }
        }
    }
}
