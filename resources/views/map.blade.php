@extends('layouts.app')
<script type='text/javascript'>
    var centreGot = false;
</script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=falsesensor=false&v=3"></script>
<link href="{{ asset('public/css/map/map.css') }}" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script type='text/javascript' src="public/js/map.js"></script>


@section('content')
<div class="container">
    <div class="content">
        <div class="get_cities">
            <div class="field first_field">
                <label>City Name: </label>
                <input type="text" id="city_name" name="cities" value="" />
                <ul class="names_option">

                </ul>
            </div>
            <div class="field">
                <label>Count of Cities: </label>
                <input type="number" id="city_count" name="city_numbers" value="20" min="1" max="50" />
            </div>
            <div class="field">
                <label>City Name language: </label>
                <select id="city_lang" name="lang">
                    <option value="ru">ru</option>
                    <option value="en">en</option>
                </select>
            </div>
            <input id="get_cities" type="button" name="getcities" value="Get Sities" />
        </div>
        <div id="map" style="height: 500px;"></div>
    </div>
</div>
@endsection