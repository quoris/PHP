@extends('layouts.app')

@section('title', 'Информация о состоянии SEO-параметров выбранной страницы')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Значения отслеживаемых параметров</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('parameters') }}">
                            {{ csrf_field() }}

                            @if(!empty($showParametersError))
                                <div class="alert alert-danger">
                                    {{ $showParametersError }}
                                </div>
                            @else
                                <p>Здесь представлен список всех отслеживаемых параметров страницы <a href="{{ $page }}" target="_blank"><b><span style="color: rgb(82, 167, 218);">{{ $page }}</span></b></a>
                                <p>Напротив каждого из них вы можете увидеть информацию об изменении параметра и дате его обновления. <button class="btn btn-primary" name="goToPages">К списку страниц</button></p>

                                <input type="hidden" name="currentPageParameters" value="{{ $page }}">

                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Название параметра</th><th>Значение</th><th>Статус</th><th>Дата проверки</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($changeParametersInfo as $cpi)
                                        <tr>
                                            <td>{{ $cpi['parameter'] }}</td><td>{{ $cpi['value'] }}</td><td>{{ $cpi['status'] }}</td><td>{{ $cpi['time'] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection