@extends('layouts.master')

@section('extraStyle')
    <link href="{{ asset('css/checkbox-radio-input.css') }}" rel="stylesheet">
    <style>
        main::after {
        content: "";
        background: url("/images/bg.png");
        opacity: 0.3;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        position: absolute;
        z-index: -1;
        }
    </style>
@endsection

@section('content')
    <!-- side bar -->
    @include('balde_components.navs.side-bar')                       
    <!-- top nav bar -->
    @include('balde_components.navs.nav-bar-v2')
    {{-- main form  --}}
    <main class="w-full min-h-screen mt-16  relative  flex flex-row justify-center py-10" >
        <div class="w-full container md:w-5/6  lg:w-3/4 ">
            <form class="w-full rounded mb-3 shadow-lg" method="POST" action="{{route("review",['market'=>$market->id])}}">
                @csrf
                <header class="w-full bg-green text-white text-xl rounded-t-sm p-4 font-bold text-left">
                  {{auth()->user()->name}} > {{__("Leave a review for")}}  {{$market->name}}
                </header>
                <div class="bg-white grid grid-cols-4 gap-6  p-4 rounded-b-sm" > 
                    @if ($errors->any())
                        <div class="col-span-4 alert alert-danger m-1" >
                            {{$errors->first()}}
                        </div>
                    @endif
                    <div class="md:col-span-4 col-span-4 text-gray-800 text-sm font-semibold mb-1">
                        <span class="text-xs md:text-sm">{{__("Your Rate")}} </span>
                        <span id="quality_value">0</span>
                        <div class="slider-container  py-2 ">
                            <span class="bar"><span id="quality_fill" class="fill block w-full h-2 rounded "></span></span>
                            <input type="range" required id="quality" name="rate" 
                            class=" range_v2 slider relative z-20 appearance-none border-1 border-gray-300 -mt-2 w-full h-2 rounded outline-none  bg-transparent " 
                            min="0" max="5" value="0" step="1" style="border: 1px solid #ccc">
                        </div>
                    </div>
                    <div class="col-span-4">
                        <h2 class="text-gray-800 text-sm font-semibold mb-2">{{__("Your review")}} </h2>
                        <div class="col-span-6 md:col-span-3  rounded  shadow-md">
                            <textarea  
                                placeholder="{{__("Add your review here")}} .............."
                                class="mt-1 p-3 focus:ring-white focus:border-white block w-full shadow-sm sm:text-sm  border-1 border-gray-500 rounded outline-none @error('review')   border-2 border-red-300 @enderror "
                                cols="30" 
                                rows="5"
                                name="review"
                                required
                            ></textarea>
                        </div>
                    </div>
                    <div class="lg:col-span-3 md:col-span-2 col-span-4"></div>
                    <div class="col-span-4 md:col-span-2 lg:col-span-1 flex flex-row">
                        <a href="/markets/{{$market->id}}" class="mb-2 p-2 mx-2 text-gray-400 hover:text-gray-700  font-semibold">
                           {{__('cancel')}}  
                        </a>
                        <button type="submit" class="form-control mb-2 p-2 border-none bg-green text-white font-semibold">
                           {{__('Submit review')}}  
                        </button>
                    </div>                           
                </div>
            </form>
        </div>     
    </main>
    {{-- footer page --}}
    @include('balde_components.footer')
@endsection
@section('extraJs')
    <script>
        var quality_range = document.getElementById("quality");
        var quality_fill = document.getElementById("quality_fill");
        var quality_value=document.getElementById('quality_value');
        if(quality_range && quality_fill && quality_value){
            quality_range.addEventListener('input',e=>{
                console.log(quality_range.value);
                quality_value.textContent=quality_range.value;
                quality_fill.style.width=(quality_range.value*20)+ '%';
            });
        }
    </script>
@endsection