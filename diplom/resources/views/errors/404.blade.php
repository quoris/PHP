@extends('layouts.app')

@section('title', 'Вы попали на несуществующую страницу')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Без паники.</div>
                    <div class="panel-body">
                        <p>Это обычная не существующая страница. Просто вы попали</p>
                        <p><img src={{ asset('images/page404.jpg') }} width="640" height="480" alt="Cтраница 404" title="Вы попали на несуществующую страницу"></p>
                        <p>Возможно, она была удалена или вы набрали неверный адрес. Пожалуйста, воспользуйтесь навигацией чтобы найти интересующую вас информацию.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection