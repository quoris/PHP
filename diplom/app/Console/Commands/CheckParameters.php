<?php

namespace App\Console\Commands;

use App\SendEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use phpQuery;

class CheckParameters extends Command
{
    /**
     * Название консольной команды
     *
     * @var string
     */
    protected $signature = 'checkParameters';

    /**
     * Описание консольной команды
     *
     * @var string
     */
    protected $description = 'check seo-parameters changes';

    /**
     * Конструктор.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Логика проверки состояния отслеживаемых SEO-параметров.
     *
     * @return mixed
     */
    public function handle()
    {

        // Проверка окончания регистрации доменов


        $domains = DB::table('sites')->pluck('url_site');

        foreach ($domains as $d)
        {
            $c = curl_init();
            curl_setopt($c, CURLOPT_URL, "https://www.nic.ru/whois/?query=".$d);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)");
            $paidTillContentPage = curl_exec($c);
            curl_close($c);

            $dom = phpQuery::newDocument($paidTillContentPage);

            $domainPaidTillPattern = "!paid-till:(.*?)<br>!si";
            preg_match($domainPaidTillPattern, $dom, $domainPaidTillContent);

            $auxArr = explode(" ", $domainPaidTillContent[1]);
            $PaidTillDateWithoutSpaces = "";

            foreach ($auxArr as $ar)
            {
                if(strpos($ar, "20") !== false)
                {
                    $tmpVar = trim($ar);
                    $PaidTillDateWithoutSpaces .= $tmpVar;
                }
            }

            if(strpos($PaidTillDateWithoutSpaces, "T") !== false)
            {
                $incorrectPaidTillDate = explode("T", $domainPaidTillContent[1]);
                $auxArrInvertedPTD = explode("-", $incorrectPaidTillDate[0]);

            } else {
                $auxArrInvertedPTD = explode(".", $PaidTillDateWithoutSpaces);
            }

            $tmpOne = mb_substr($auxArrInvertedPTD[0], -5, 5, 'UTF-8');
            $tmpTwo = trim($tmpOne);
            $auxArrInvertedPTD[0] = $tmpTwo;

            $auxArrCorrectPTD = array_reverse($auxArrInvertedPTD);
            $correctPaidTillDate = implode(".", $auxArrCorrectPTD);

            DB::table('sites')->where('url_site', $d)->update(['paid_till' => $correctPaidTillDate]);
        }


        // Проверка изменения SEO-параметров


        $pages = DB::table('pages')->pluck('url_page', 'id');

        foreach ($pages as $id => $page)
        {

            // Проверка изменения поискового редиректа


            $curl = curl_init();
            curl_setopt($curl,CURLOPT_URL,$page);
            curl_setopt($curl,CURLOPT_HTTPHEADER, array(
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
                "Accept-Encoding: gzip, deflate, sdch, br",
                "Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4",
                "Cache-Control: max-age=0",
                "Connection: keep-alive",
                "Upgrade-Insecure-Requests: 1",
                "User-Agent: Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)"
            ));
            curl_setopt($curl,CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_NOBODY,true);
            curl_setopt($curl,CURLOPT_HEADER,true);
            $outSearchHeaders = curl_exec($curl);
            curl_close($curl);

            $explodeSearchHeaders = explode("\n", $outSearchHeaders);
            $answerCodeLine = $explodeSearchHeaders[0];

            $answerCodePattern = "!HTTP/[.A-Za-z0-9]*\s(.*?)\s!si";
            preg_match($answerCodePattern, $answerCodeLine, $codeContent);

            if(@$codeContent[1] == 304)
            {
                DB::table('values')->where('page_id', $id)->where('parameter_id', '1')->update(['status' => "Без изменений"]);
                DB::table('values')->where('page_id', $id)->where('parameter_id', '1')->update(['value' => $codeContent[1]]);
                DB::table('values')->where('page_id', $id)->where('parameter_id', '2')->update(['status' => "Без изменений"]);
                DB::table('values')->where('page_id', $id)->where('parameter_id', '3')->update(['status' => "Без изменений"]);
                DB::table('values')->where('page_id', $id)->where('parameter_id', '4')->update(['status' => "Без изменений"]);
                DB::table('values')->where('page_id', $id)->where('parameter_id', '5')->update(['status' => "Без изменений"]);
                DB::table('values')->where('page_id', $id)->where('parameter_id', '6')->update(['status' => "Без изменений"]);
                DB::table('values')->where('page_id', $id)->where('parameter_id', '7')->update(['status' => "Без изменений"]);
                DB::table('values')->where('page_id', $id)->where('parameter_id', '8')->update(['status' => "Без изменений"]);
                DB::table('values')->where('page_id', $id)->where('parameter_id', '9')->update(['status' => "Без изменений"]);
                DB::table('values')->where('page_id', $id)->where('parameter_id', '10')->update(['status' => "Без изменений"]);
                DB::table('values')->where('page_id', $id)->where('parameter_id', '11')->update(['status' => "Без изменений"]);
                DB::table('values')->where('page_id', $id)->where('parameter_id', '12')->update(['status' => "Без изменений"]);
                DB::table('values')->where('page_id', $id)->where('parameter_id', '13')->update(['status' => "Без изменений"]);
                DB::table('values')->where('page_id', $id)->where('parameter_id', '14')->update(['status' => "Без изменений"]);

            } else {

                $redirectCodePattern = "!30[0-7]!si";
                $isSearchRedirect = 0;

                if(preg_match($redirectCodePattern, @$codeContent[1]))
                {
                    foreach ($explodeSearchHeaders as $esh)
                    {
                        if(strpos($esh,'location:') !== false)
                        {
                            $isSearchRedirect++;
                        }
                    }
                }

                $searchRedirectFromDB = DB::table('values')->where('page_id', $id)->where('parameter_id', '2')->value('value');

                if($isSearchRedirect == $searchRedirectFromDB)
                {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '2')->update(['status' => "Без изменений"]);
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '2')->update(['value' => $isSearchRedirect]);
                } else {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '2')->update(['status' => "Изменен"]);
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '2')->update(['value' => $isSearchRedirect]);
                }


                // Проверка изменения кода ответа сервера


                $answerCodeFromDB = DB::table('values')->where('page_id', $id)->where('parameter_id', '1')->value('value');

                if($answerCodeFromDB == @$codeContent[1]) {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '1')->update(['status' => "Без изменений"]);
                } else {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '1')->update(['value' => $codeContent[1]]);
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '1')->update(['status' => "Изменен"]);
                    echo error_get_last();
                }


                // Проверка изменения мобильного редиректов


                $curl = curl_init();
                curl_setopt($curl,CURLOPT_URL,$page);
                curl_setopt($curl,CURLOPT_HTTPHEADER, array(
                    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
                    "Accept-Encoding: gzip, deflate, sdch, br",
                    "Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4",
                    "Cache-Control: max-age=0",
                    "Connection: keep-alive",
                    "Upgrade-Insecure-Requests: 1",
                    "User-Agent: Mozilla/5.0 (Android; Mobile; rv:13.0) Gecko/13.0 Firefox/13.0"
                ));
                curl_setopt($curl,CURLOPT_FOLLOWLOCATION, false);
                curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
                curl_setopt($curl,CURLOPT_NOBODY,true);
                curl_setopt($curl,CURLOPT_HEADER,true);
                $outMobileHeaders = curl_exec($curl);
                curl_close($curl);

                $explodeMobileHeaders = explode("\n", $outMobileHeaders);
                $answerCodeLine = $explodeMobileHeaders[0];

                $answerCodePattern = "!HTTP/[.A-Za-z0-9]*\s(.*?)\s!si";
                preg_match($answerCodePattern, $answerCodeLine, $codeContent);

                $redirectCodePattern = "!30[0-7]!si";
                $isMobileRedirect = 0;

                if(preg_match($redirectCodePattern, @$codeContent[1]))
                {
                    foreach ($explodeMobileHeaders as $esh)
                    {
                        if(strpos($esh,'location:') !== false)
                        {
                            $isMobileRedirect++;
                        }
                    }
                }

                $mobileRedirectFromDB = DB::table('values')->where('page_id', $id)->where('parameter_id', '3')->value('value');

                if($isMobileRedirect == $mobileRedirectFromDB)
                {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '3')->update(['status' => "Без изменений"]);
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '3')->update(['value' => $isMobileRedirect]);
                } else {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '3')->update(['status' => "Изменен"]);
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '3')->update(['value' => $isMobileRedirect]);
                }


                // Проверка изменения robots.txt


                $siteId = DB::table('pages')->where('id', $id)->value('site_id');
                $siteName = DB::table('sites')->where('id', $siteId)->value('url_site');

                $c = curl_init();
                curl_setopt($c, CURLOPT_URL, $siteName."/robots.txt");
                curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)");
                $robotsTxtContent = curl_exec($c);
                curl_close($c);

                $lengthCurrentRobotsTxt = strlen($robotsTxtContent);
                $lengthRobotsTxtFromDB = DB::table('values')->where('page_id', $id)->where('parameter_id', '4')->value('value');

                if($lengthRobotsTxtFromDB == $lengthCurrentRobotsTxt) {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '4')->update(['status' => "Без изменений"]);
                } else {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '4')->update(['value' => $lengthCurrentRobotsTxt]);
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '4')->update(['status' => "Изменен"]);
                }


                // Проверка изменения sitemap.xml


                $c = curl_init();
                curl_setopt($c, CURLOPT_URL, $siteName."/sitemap.xml");
                curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)");
                $sitemapXmlContent = curl_exec($c);
                curl_close($c);

                $lengthCurrentSitemapXml = strlen($sitemapXmlContent);
                $lengthSitemapXmlFromDB = DB::table('values')->where('page_id', $id)->where('parameter_id', '5')->value('value');

                if($lengthSitemapXmlFromDB == $lengthCurrentSitemapXml) {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '5')->update(['status' => "Без изменений"]);
                } else {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '5')->update(['value' => $lengthCurrentSitemapXml]);
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '5')->update(['status' => "Изменен"]);
                }


                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $page);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)");
                $sourceCodeCurrentPage = curl_exec($ch);
                curl_close($ch);


                // Проверка изменения title


                $dom = phpQuery::newDocument($sourceCodeCurrentPage);
                $title = $dom->find('title')->text();

                $lengthCurrentTitle = mb_strlen($title);
                $lengthTitleFromDB = DB::table('values')->where('page_id', $id)->where('parameter_id', '6')->value('value');

                if($lengthTitleFromDB == $lengthCurrentTitle) {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '6')->update(['status' => "Без изменений"]);
                } else {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '6')->update(['value' => $lengthCurrentTitle]);
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '6')->update(['status' => "Изменен"]);
                }


                // Проверка изменения description


                $description = $dom->find('meta[name=description]')->attr('content');

                $lengthCurrentDescription = mb_strlen($description);
                $lengthDescriptionFromDB = DB::table('values')->where('page_id', $id)->where('parameter_id', '7')->value('value');

                if($lengthDescriptionFromDB == $lengthCurrentDescription) {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '7')->update(['status' => "Без изменений"]);
                } else {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '7')->update(['value' => $lengthCurrentDescription]);
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '7')->update(['status' => "Изменен"]);
                }


                // Проверка изменения keywords


                $keywords = $dom->find('meta[name=keywords]')->attr('content');

                $lengthCurrentKeywords = mb_strlen($keywords);
                $lengthKeywordsFromDB = DB::table('values')->where('page_id', $id)->where('parameter_id', '8')->value('value');

                if($lengthKeywordsFromDB == $lengthCurrentKeywords) {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '8')->update(['status' => "Без изменений"]);
                } else {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '8')->update(['value' => $lengthCurrentKeywords]);
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '8')->update(['status' => "Изменен"]);
                }


                // Проверка изменения h1


                $hOne = $dom->find('h1')->text();

                $lengthCurrentHOne = mb_strlen($hOne);
                $lengthHOneFromDB = DB::table('values')->where('page_id', $id)->where('parameter_id', '9')->value('value');

                if($lengthHOneFromDB == $lengthCurrentHOne)
                {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '9')->update(['status' => "Без изменений"]);
                } else {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '9')->update(['value' => $lengthCurrentHOne]);
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '9')->update(['status' => "Изменен"]);
                }


                // Проверка изменения rel="alternate"


                $linkTags = $dom->find('link');
                $isThereAlternate = 0;

                foreach ($linkTags as $lt)
                {
                    if(pq($lt)->attr('rel') == "alternate")
                    {
                        $isThereAlternate++;
                    }
                }

                $isThereAlternateInDB = DB::table('values')->where('page_id', $id)->where('parameter_id', '10')->value('value');

                if($isThereAlternateInDB == $isThereAlternate)
                {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '10')->update(['status' => "Без изменений"]);
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '10')->update(['value' => $isThereAlternate]);
                } else {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '10')->update(['status' => "Изменен"]);
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '10')->update(['value' => $isThereAlternate]);
                }


                // Проверка изменения rel="canonical"


                $isThereCanonical = 0;

                foreach ($linkTags as $lt)
                {
                    if(pq($lt)->attr('rel') == "canonical")
                    {
                        $isThereCanonical++;
                    }
                }

                $isThereCanonicalInDB = DB::table('values')->where('page_id', $id)->where('parameter_id', '11')->value('value');

                if($isThereCanonicalInDB == $isThereCanonical) {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '11')->update(['status' => "Без изменений"]);
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '11')->update(['value' => $isThereCanonical]);
                } else {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '11')->update(['status' => "Изменен"]);
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '11')->update(['value' => $isThereCanonical]);
                }


                // Проверка изменения <noindex> или <meta name="robots" content="noindex">


                $contentRobotsMetaTage = $dom->find('meta[name=robots]')->attr('content');

                $isThereMetaTageNoindex = 0;
                if(strpos($contentRobotsMetaTage, "noindex") !== false) {
                    $isThereMetaTageNoindex++;
                }

                $countTageNoindex = $dom->find('noindex')->size();
                $countNoindexFromDB = DB::table('values')->where('page_id', $id)->where('parameter_id', '12')->value('value');

                switch ($isThereMetaTageNoindex) {
                    case 0:
                        if($countTageNoindex == $countNoindexFromDB) {
                            DB::table('values')->where('page_id', $id)->where('parameter_id', '12')->update(['status' => "Без изменений"]);
                            DB::table('values')->where('page_id', $id)->where('parameter_id', '12')->update(['value' => $countTageNoindex]);
                        } else {
                            DB::table('values')->where('page_id', $id)->where('parameter_id', '12')->update(['status' => "Изменен"]);
                            DB::table('values')->where('page_id', $id)->where('parameter_id', '12')->update(['value' => $countTageNoindex]);
                        }
                        break;
                    case 1:
                        if($isThereMetaTageNoindex == $countNoindexFromDB) {
                            DB::table('values')->where('page_id', $id)->where('parameter_id', '12')->update(['status' => "Без изменений"]);
                            DB::table('values')->where('page_id', $id)->where('parameter_id', '12')->update(['value' => '1']);
                        } else {
                            DB::table('values')->where('page_id', $id)->where('parameter_id', '12')->update(['status' => "Изменен"]);
                            DB::table('values')->where('page_id', $id)->where('parameter_id', '12')->update(['value' => '1']);
                        }
                        break;
                }


                // Проверка изменения rel="nofollow" или <meta name="robots" content="nofollow">


                $isThereMetaTageNofollow = 0;
                if(strpos($contentRobotsMetaTage, "nofollow") !== false) {
                    $isThereMetaTageNofollow++;
                }

                $countAttributeNofollow = $dom->find('a[rel=nofollow]')->size();
                $countNofollowFromDB = DB::table('values')->where('page_id', $id)->where('parameter_id', '13')->value('value');

                switch ($isThereMetaTageNofollow) {
                    case 0:
                        if($countAttributeNofollow == $countNofollowFromDB) {
                            DB::table('values')->where('page_id', $id)->where('parameter_id', '13')->update(['status' => "Без изменений"]);
                            DB::table('values')->where('page_id', $id)->where('parameter_id', '13')->update(['value' => $countAttributeNofollow]);
                        } else {
                            DB::table('values')->where('page_id', $id)->where('parameter_id', '13')->update(['status' => "Изменен"]);
                            DB::table('values')->where('page_id', $id)->where('parameter_id', '13')->update(['value' => $countAttributeNofollow]);
                        }
                        break;
                    case 1:
                        if($isThereMetaTageNofollow == $countNofollowFromDB) {
                            DB::table('values')->where('page_id', $id)->where('parameter_id', '13')->update(['status' => "Без изменений"]);
                            DB::table('values')->where('page_id', $id)->where('parameter_id', '13')->update(['value' => '1']);
                        } else {
                            DB::table('values')->where('page_id', $id)->where('parameter_id', '13')->update(['status' => "Изменен"]);
                            DB::table('values')->where('page_id', $id)->where('parameter_id', '13')->update(['value' => '1']);
                        }
                        break;
                }


                // Проверка изменения микрорамзетки Open Graph


                $currentCountMarkup = $dom->find('meta[property]')->size();
                $countMarkupFromDB = DB::table('values')->where('page_id', $id)->where('parameter_id', '14')->value('value');

                if($countMarkupFromDB == $currentCountMarkup) {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '14')->update(['status' => "Без изменений"]);
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '14')->update(['value' => $currentCountMarkup]);
                } else {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '14')->update(['status' => "Изменен"]);
                    DB::table('values')->where('page_id', $id)->where('parameter_id', '14')->update(['value' => $currentCountMarkup]);
                }


                // Установка индикатора изменения страниц


                $pageIndicator = 0;
                $statuses = DB::table('values')->where('page_id', $id)->pluck('status');

                foreach ($statuses as $st)
                {
                    if($st == 'Изменен') $pageIndicator++;
                }

                DB::table('pages')->where('id', $id)->update(['is_page_changed' => $pageIndicator]);


                // Установка даты проведенной проверки


                $checkTime = date('d.m.Y');

                $allParameters = DB::table('parameters')->pluck('id');
                foreach ($allParameters as $ap)
                {
                    DB::table('values')->where('page_id', $id)->where('parameter_id', $ap)->update(['time' => $checkTime]);
                }
            }
        }


        // Установка индикатора изменения сайтов


        $sitesIds = DB::table('sites')->pluck('id');

        foreach ($sitesIds as $si)
        {
            $currentSitePagesChangeId = DB::table('pages')->where('site_id', $si)->pluck('is_page_changed');

            $tmpId = 0;
            foreach ($currentSitePagesChangeId as $cspci)
            {
                if($cspci != 0) $tmpId++;
            }

            if($tmpId == 0)
            {
                DB::table('sites')->where('id', $si)->update(['is_site_changed' => 0]);
            } else {
                DB::table('sites')->where('id', $si)->update(['is_site_changed' => 1]);
            }
        }


        // отправка писем владельцам сайтов об изменениях
        $se = new SendEmail();
        $se->getSitesAndUsersData();
    }
}