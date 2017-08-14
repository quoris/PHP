@extends('layouts.app')

@section('title', 'Добавление страниц выбранного сайта для отслеживания')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Добавление адресов отслеживаемых страниц.</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action=" {{ url('/addPages') }}">
                            {{ csrf_field() }}

                                @if(!empty($addPagesReceivedUrlError))
                                    <div class="alert alert-danger">
                                        {{ $addPagesReceivedUrlError }}
                                    </div>
                                @else

                                @if(Session::has('countUrlError'))
                                    <div class="alert alert-danger">
                                        {{ Session::get('countUrlError') }}
                                    </div>
                                @endif

                                @if(Session::has('wrongUrlError'))
                                    <div class="alert alert-danger">
                                        {{ Session::get('wrongUrlError') }}
                                    </div>
                                @endif

                                @if(Session::has('alreadyIsError'))
                                    <div class="alert alert-danger">
                                        Эти страницы уже добавлены:<br><br>
                                        @foreach(Session::get('alreadyIsError') as $ai)
                                            <p>{{ $ai }}</p>
                                        @endforeach
                                    </div>
                                @endif

                                <p> Вы добавляете страницы для сайта {{ $siteUrl }}</p>
                                <input type="hidden" name="sitePagesWhichAdded" value="{{ $siteUrl }}">

                                <p>Добавьте адреса тех страниц, у которых вы хотите отслеживать изменение SEO-параметров.<br>Все url'ы добавляются сразу (если не были найдено ошибок). Максимум 50 url'ов на сайт.</p>

                                <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                                    <div class="col-md-8">
                                        <textarea rows="10" class="form-control" name="pages" pattern="^http(?:s?)://(?:(?:[0-9А-Я-.]+)|(?:[0-9a-z-.]+))\.(?:(?:[А-Я-.]+)|(?:[0-9a-z-.]+))/?$"></textarea>
                                    </div>
                                </div>

                                <div>
                                    <p></p>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-2">
                                        <button type="submit" class="btn btn-primary">
                                            Добавить
                                        </button>
                                        <a class="btn btn-link" href="{{ url('/home') }}">
                                            Добавить позже
                                        </a>
                                    </div>
                                </div>
                            @endif

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection