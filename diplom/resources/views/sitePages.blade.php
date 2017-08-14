@extends('layouts.app')

@section('title', 'Список всех добавленных страниц выбранного сайтов')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Отслеживаемые страницы.</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('sitePages') }}">
                            {{ csrf_field() }}

                            @if(!empty($showSiteError))
                                <div class="alert alert-danger">
                                    {{ $showSiteError }}
                                </div>
                            @else
                                <input type="hidden" name="siteNamePageWhichNeedToAdd" value="{{ $sn }}">

                                <p>На этой странице находится список страниц сайта {{ $sn }}, находящихся на мониторинге.</p>
                                <p>Зеленым цветом отмечены страницы, у которых по результатам последней проверки значения SEO-параметров не изменились. Красным отмечены те, у которых они изменились.</p>
                                <p><b>Внимание!</b> Срок окончания регистрации домена истекает
                                    @if(!empty($pt))
                                        <b>{{ $pt }}</b>
                                    @else
                                        <b>(определиться после первой проверки)</b>
                                    @endif
                                </p>
                                <br>
                                <p>Вы можете добавить еще несколько страниц
                                    <button type="submit" class="btn btn-primary" name="addPages">
                                        Добавить страницы
                                    </button>
                                    или перейти
                                    <button class="btn btn-primary" name="goToSites">
                                        К списку сайтов
                                    </button>
                                </p>

                                @if(count($sp) != 0)
                                    <table class="table">
                                        @foreach($sp as $spAux)
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <a href="{{ url('parameters') }}/{{ $spAux['pageId'] }}" name="currentSitePage">{{ $spAux['pageUrl'] }}</a>
                                                </td>
                                                <td>
                                                    <button type="submit" class="btn btn-primary btn-sm" name="deletePage" value="{{ $spAux['pageId'] }}">
                                                        Удалить
                                                    </button>
                                                </td>
                                                <td>
                                                    @if($spAux['indicator'] == 0)
                                                        <img src={{ asset('images/greenIndicator.jpg') }}>
                                                    @else
                                                        <img src={{ asset('images/redIndicator.jpg') }}>
                                                    @endif
                                                </td>
                                            </tr>
                                            </tbody>
                                        @endforeach
                                    </table>
                                @else
                                    <div class="alert alert-warning">
                                        Вы пока не добавили не одной страницы. <b>Пожалуйста, добавьте их</b>.
                                    </div>
                                @endif
                            @endif

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection