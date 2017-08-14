@extends('layouts.app')

@section('title', 'О сервисе')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Информация о сервисе.</div>
                    <div class="panel-body">
                        <p><b>SEO Inspector</b> - это сервис мониторинга SEO-параметров сайта, созданный Хуснутдиновым Камилем, студентом Высшей школы информационных технологий и информационных систем, в качестве
                        дипломной работы.</p>
                        <p>На данный момент функционал позволяет отслеживать изменения следующих SEO-параметров:
                        <ul>
                            <li>Код ответа сервера</li>
                            <li>Поисковый редирект</li>
                            <li>Мобильный редирект</li>
                            <li>Файл robots.txt</li>
                            <li>Файл sitemap.xml</li>
                            <li>Тег title</li>
                            <li>Мета-тег description</li>
                            <li>Мета-тег keywords</li>
                            <li>Тег h1</li>
                            <li>Атрибут alternate</li>
                            <li>Атрибут canonical</li>
                            <li>Тег &lt;noindex&gt; или &lt;meta name="robots" content="noindex"&gt;</li>
                            <li>Атрибут nofollow или &lt;meta name="robots" content="nofollow"&gt;</li>
                            <li>Разметка Open Graph</li>
                        </ul>
                        </p>
                        <p>Данный сервис можно рассматривать в качестве внутреннего инструмента SEO-агенства.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection