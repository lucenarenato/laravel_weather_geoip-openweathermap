@extends('layout.layout')

@section('content')
<div class="container">

    <h1>Weather Forecast <span class="text-muted">|</span> {{($geoIParr['city'].', '.$geoIParr['state'])}}</h1>

<div class="row">


@foreach( $forecastData['list'] as $forecast)

    <div class="col-lg-2 col-md-3 col-sm-10 col-10">


    <h5 class="">{{date('l F dS Y gA',strtotime($forecast['dt_txt'])) }}</h5>


    @foreach($forecast['weather'] as  $weather )


     <img class="card-img-bottom" src="http://openweathermap.org/img/wn/{{$weather['icon']}}@2x.png" title="{{$weather['main']}}" alt="{{$weather['main']}}">
                <h4 class="card-title">{{ucwords($weather['description'])}}</h4>

    @endforeach

    <p class="">Temp: {{$forecast['main']['temp']}}&#8457;</p>
    <p class="">Min Temp: {{$forecast['main']['temp_min']}}&#8457;  Max Temp:  {{$forecast['main']['temp_max']}}&#8457;</p>
    <p class="">Pressure:  {{$forecast['main']['pressure']}}</p>
    <p class="">Sea Level:  {{$forecast['main']['sea_level']}}</p>
    <p class="">Ground Level:  {{$forecast['main']['grnd_level']}}</p>
    <p class="">Humidity:  {{$forecast['main']['humidity']}}</p>
    <p class="">Temp Kf:  {{$forecast['main']['temp_kf']}}</p>


    </div>


@endforeach


</div>


</div>

@endsection

