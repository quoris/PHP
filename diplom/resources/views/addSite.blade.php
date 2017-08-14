@extends('layouts.app')

@section('title', 'Добавление сайта для отслеживания его SEO-параметров')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Добавление сайта.</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action=" {{ url('/addSite') }} ">
                        {{ csrf_field() }}

                        @if(isset($belongError))
                            <div class="alert alert-danger">
                            {{ $belongError }}
                            </div>
                        @endif

                        @if(isset($addError))
                            <div class="alert alert-danger">
                            {{ $addError }}
                            </div>
                        @endif

                        <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                            <label for="url" class="col-md-4 control-label">URL</label>

                            <div class="col-md-6">
                                <input id="url" type="text" class="form-control" name="url" placeholder="(с указанием протокола)">
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="col-md-4 control-label">Название сайта</label>

                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control" name="description" required autofocus>
                            </div>
                        </div>

                        <p>Пройдите проверку, чтобы подтвердить, что домен принадлежит вам. Выберите один из вариантов:</p>
                        <p>
                            <ol>
                            <li>Скачайте HTML-файл: <a href="{{ url('/seoinspector-verification273ty9ymd9521p28.html') }}" download>seoinspector-verification273ty9ymd9521p28.html</a></li>
                            <li>Загрузите его в корневой каталог вашего веб-сервера</li>
                            <li>Убедитесь, что загруженный файл открывается по адресу: <a href="" target="_blank" class="generatedurl"><span class="openurl"></span>/seoinspector-verification273ty9ymd9521p28.html</a></li>
                            <li>Нажмите кнопку "Подтвердить"</li>
                            </ol>
                        </p>

                        <!-- Вывод url для проверки загрузки файла -->
                        <script>
                            $(document).ready ( function(){

                                $("#url").keyup(function() {
                                    $('a.generatedurl .openurl').text($("#url").val());
                                    $('a.generatedurl').attr("href", $("a.generatedurl").text());
                                });
                            });
                        </script>
                        <!-- Вывод url для проверки загрузки файла -->

                        <p>Альтернативный вариант:</p>
                        <p>
                        <ol>
                            <li>Разместите в коде главной страницы сайта в раздел &lt;head&gt;...&lt;/head&gt; мета-тег:<br>
                                <input id="verification" type="text" class="form-control" name="verification" value='<meta name="seoinspector-verification" content="273ty9ymd9521p28"/>' readonly="readonly">
                            </li>
                        </ol>
                        </plaintext>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Подтвердить
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection