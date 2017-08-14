@extends('layouts.app')

@section('title', 'Список всех добавленных сайтов')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Добро пожаловать!</div>
                <div class="panel-body">

                        <p>На этой странице находится список сайтов, находящихся на мониторинге.</p>
                        <p>Зеленым цветом отмечены сайты, у которых по результатам последней проверки значения SEO-параметров не изменились. Красным отмечены те, у которых они изменились.</p>
                        <p><a href="{{ url('/addSite') }}"><button class="btn btn-primary">Добавить сайт</button></a></p>

                        @if(!empty($userSites))
                            <p>Ваши сайты:</p>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Название</th><th>Количество страниц</th><th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userSites as $us)
                                        <tr>
                                            <form class="form-horizontal" role="form" method="POST" action="{{ url('home') }}">
                                            {{ csrf_field() }}

                                                <input type="hidden" name="siteId" value="{{ $us['site_id'] }}">
                                                <td>
                                                    {{ $us['description'] }}<br>
                                                    {{ $us['url'] }}
                                                </td>
                                                <td>
                                                    {{ $us['countPages'] }}
                                                </td>
                                                <td>
                                                    <button type="submit" class="btn btn-primary btn-sm" name="deleteSite">
                                                        Удалить
                                                    </button>
                                                    <button type="submit" class="btn btn-primary btn-sm" name="showSitePages">
                                                        Страницы сайта
                                                    </button>
                                                </td>
                                                <td>
                                                    @if($us['indicator'] == 0)
                                                        <img src={{ asset('images/greenIndicator.jpg') }}>
                                                    @else
                                                        <img src={{ asset('images/redIndicator.jpg') }}>
                                                    @endif
                                                </td>
                                            </form>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        @else
                            <p>Вы пока не добавили ни один сайт.</p>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection