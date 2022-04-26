@extends('layouts.master')

@section('extraStyle')
<link href="{{ asset('css/checkbox-radio-input.css') }}" rel="stylesheet">
<link href="{{ asset('css/detail-restaurant-tabs.css') }}" rel="stylesheet">
@endsection

@section('content')
    <!-- side bar -->
    @include('balde_components.navs.side-bar')                       
    <!-- top nav bar -->
    @include('balde_components.navs.nav-bar-v2')

    <main class="w-full mt-16" >
        <!-- top banner  -->
		<div class="w-full relative" style="height: 65vh;">
            <img src={{$market_cover}}  alt="just an image" class="absolute object-cover top-0 left-0 w-full h-full">
            <div class="absolute top-0 left-0 w-full h-full bg-black opacity-40"></div>
            <div class="relative container py-4 w-full h-full flex-col flex justify-end">
                <div class="text-base pt-1 mb-1.5 w-max flex flex-row items-end">
                    <div class="bg-black p-1 w-max rounded mr-1">
                        @if($market->rate)
                            <span class="text-white p-1  font-semibold">
                                {{$market->rate}}
                            </span>
                            <i class="fas fa-star text-gold"></i>
                        @else
                        <span class="text-white p-1  font-semibold">
                            {{__('New')}}
                        </span>
                        @endif    
                    </div>
                    <em class="text-xs font-bold text-white">
                        {{count($market->marketReviews)}} {{__("Reviews")}}
                    </em>
                </div>
                <div class="text-white lg:text-5xl text-2xl font-semibold pt-1">{{$market->name}}</div>
                <div class="text-white items-center text-base flex flex-col md:flex-row md:justify-between">
                   <div class="w-full mt-1 md:my-0 md:w-max">
                        {{$market->address}}
                   </div>
                   <div class="w-full my-1 md:my-0 md:w-max">
                        <button onclick='window.open("https://www.google.com/maps/dir//{{$market->latitude}},{{$market->longitude}}/@${{$market->latitude}},{{$market->longitude}},15z")' class="bg-white text-black px-3 py-2.5 rounded hover:text-gray-400 mr-1" > 
                            <i class="fas fa-route "></i> {{__("Get directions")}} 
                        </button>
                   </div>
                </div>
            </div>
        </div>
        <!-- Market details -->
        <market-details @auth :idAuth={{auth()->id()}} @endauth 
            :market_id={{$market->id}} 
            :currency_right={{setting('currency_right', false)}}
            :default_currency="`{{setting('default_currency')}}`"/>
       
	</main>
    @include('balde_components.footer')

@endsection

@section('extraJs')
    <script>
        function show_make_orde(btn) {
            let div=btn.nextElementSibling;
            Array.from(document.querySelectorAll('.order')).forEach(element => {
                if(div !== element){
                    element.classList.add('hidden');
                } 
            });
            Array.from(document.querySelectorAll('.make_order')).forEach(element => {
                if(btn !== element){
                    element.classList.add('fa-plus');
                    element.classList.add('text-green');
                } 
            });        
            div.classList.toggle('hidden');
            btn.classList.toggle('fa-plus');
            btn.classList.toggle('fa-times');
            btn.classList.toggle('text-green');
            btn.classList.toggle('text-red-500'); 
        }
    </script>
@endsection