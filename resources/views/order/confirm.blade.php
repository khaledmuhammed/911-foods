@extends('layouts.master')
@section('extraStyle')
    <link href="{{ asset('css/confirm-delivery.css') }}" rel="stylesheet">
@endsection

@section('content')
    @include('balde_components.navs.side-bar')                       
    @include('balde_components.navs.nav-bar-v2')
    <main class="w-full mt-16 flex flex-row justify-center" >
        <div class="container relative w-4/5 md:w-3/5 lg:w-1/3 mx-0 mt-10 shadow-2xl bg-white rounded " style="height: 500px;"  >
            <!-- the checkmark svg have a costum css  -->
            <svg class="checkmark" viewBox="0 0 52 52">
                <circle class="checkmark-circle" fill="none" cx="26" cy="26" r="25" />
                <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
            <div class="text-black relative flex flex-col items-center">
                <h2 class="text-3xl font-medium text-center">
                    {{__("Order Confirmed")}}
                </h2>
                <br>
                <div class="text-gray-500 text-xs w-10/12 py-2 text-center md:text-base"><b>{{auth()->user()->name}}</b> ,
                    {{__("Order Confirmed Description")}}
                    <br>
                    <b> {{__("Order num")}} : {{$order->id}}</b>
                </div>
            </div>
            <div class="redirect text-center text-white absolute bottom-0 left-0  py-2 w-full bg-green" style="height: 100px;">
                <h2 class="text-2xl font-medium">
                    {{$market->name}}
                </h2>
                <br>
                <span> {{$market->address }} &nbsp; 
                    <a onclick='window.open("https://www.google.com/maps/dir//{{$market->latitude}},{{$market->longitude}}/@${{$market->latitude}},{{$market->longitude}},15z")' 
                    class="no-underline text-gray-900 hover:text-gray-800 cursor-pointer"> 
                        |&nbsp;  {{__("Get directions")}}
                        <i class="fas fa-map-marker-alt"></i>
                    </a>
                </span>
            </div> 
        </div>
    </main>
    @include('balde_components.footer')
@endsection