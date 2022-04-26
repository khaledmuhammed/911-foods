@extends('layouts.master')
@section('extraStyle')
    <link href="{{ asset('css/checkbox-radio-input.css') }}" rel="stylesheet">
@endsection
@section('content')
    @include('balde_components.navs.side-bar')
    @include('balde_components.navs.nav-bar-v2')
    <list-markets :currency_right={{setting('currency_right', false)}} :default_currency="`{{setting('default_currency')}}`"></list-markets>
    @include('balde_components.footer') 
@endsection
@section('extraJs')
<script src="{{asset('js/maps.js')}}"></script>
@endsection
<script>
    (function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
        }

    })();

    function showPosition(position) {
        document.cookie="lat=" + position.coords.latitude;
        document.cookie="lng=" + position.coords.longitude;

console.log(position.coords.latitude)
console.log(position.coords.longitude)
    }
</script>
