    
@extends('layouts.master')
@section('extraStyle')
    <link href="{{ asset('css/confirm-delivery.css') }}" rel="stylesheet">
    <style>
        .svg-box {
            display:inline-block;
            position: relative;
            width:150px;
        }
        .red-stroke {
            stroke: #f72b07;
        }
        .circular circle.path {
            stroke-dasharray: 330;
            stroke-dashoffset: 0;
            stroke-linecap: round;
            opacity: 0.4;
            animation: 0.7s draw-circle ease-out;
        }
        .cross {
            stroke-width:6.25;
            stroke-linecap: round;
            position: absolute;
            top: 54px;
            left: 54px;
            width: 40px;
            height: 40px;
        }
        .cross .first-line {
            animation: 0.7s draw-first-line ease-out;
        }
        .cross .second-line {
            animation: 0.7s draw-second-line ease-out;
        }
        @keyframes draw-first-line {
            0% {
                stroke-dasharray: 0,56;
                stroke-dashoffset: 0;
            }

            50% {
                stroke-dasharray: 0,56;
                stroke-dashoffset: 0;
            }

            100% {
                stroke-dasharray: 56,330;
                stroke-dashoffset: 0;
            }
        }
        @keyframes draw-second-line {
            0% {
                stroke-dasharray: 0,55;
                stroke-dashoffset: 1;
            }

            50% {
                stroke-dasharray: 0,55;
                stroke-dashoffset: 1;
            }

            100% {
                stroke-dasharray: 55,0;
                stroke-dashoffset: 70;
            }
        }
    </style>
@endsection

@section('content')
    @include('balde_components.navs.side-bar')                       
    @include('balde_components.navs.nav-bar-v2')
    <main class="w-full mt-16 flex flex-row justify-center" >
        <div class="container relative w-4/5 md:w-3/5 lg:w-1/3 mx-0 mt-10 shadow-2xl bg-white rounded " style="height: 400px;"  >
            <div class="h-56">
                <svg xml:space="preserve" viewBox="0 0 100 100" y="0" x="0" xmlns="http://www.w3.org/2000/svg" id="Layer_1" version="1.1" width="47px" height="47px" xmlns:xlink="http://www.w3.org/1999/xlink" style="width:100%;height:100%;background-size:initial;background-repeat-y:initial;background-repeat-x:initial;background-position-y:initial;background-position-x:initial;background-origin:initial;background-image:initial;background-color:rgb(255, 255, 255);background-clip:initial;background-attachment:initial;animation-play-state:paused" ><g class="ldl-scale" style="transform-origin:50% 50%;transform:rotate(0deg) scale(0.8, 0.8);animation-play-state:paused" ><path fill="#f4e6c8" d="M50.5 25.1c-.2-.4-.8-.4-1 0L18.2 74.3c-.1.2-.1.5.1.7l.1.2h62.8c.1 0 .4 0 .5-.3.2-.3 0-.6 0-.6L50.5 25.1z" style="fill:rgb(244, 230, 200);animation-play-state:paused" ></path>
                    <path fill="#c33837" d="M57.2 20.6c-1.6-2.4-4.3-3.9-7.2-3.9-2.9 0-5.6 1.4-7.3 3.9L11.5 69.7c-1.8 2.7-2 6.1-.5 9 1.5 2.9 4.5 4.6 7.7 4.6h62.5c3.2 0 6.2-1.8 7.7-4.6 1.5-2.9 1.4-6.3-.4-9L57.2 20.6zm24.6 54.2c-.2.3-.4.3-.5.3H18.5l-.2-.1c-.2-.2-.2-.4-.1-.7l31.3-49.2c.3-.4.8-.4 1 0l31.3 49.1c0 .1.2.3 0 .6z" style="fill:rgb(195, 56, 55);animation-play-state:paused" ></path>
                    <path d="M61.9 52L57 47.1l-7 7-7-7-4.9 4.9 7 7-7 7 4.9 4.9 7-7 7 7 4.9-4.9-7-7z" fill="#e15c64" style="fill:rgb(221, 55, 66);animation-play-state:paused" ></path>
                    <metadata xmlns:d="https://loading.io/stock/" style="animation-play-state:paused" ><d:name style="animation-play-state:paused" >error</d:name>
                        <d:tags style="animation-play-state:paused" >fail,disable,exception,errant,bad,break,stop,error,web application</d:tags>
                        <d:license style="animation-play-state:paused" >by</d:license>
                        <d:slug style="animation-play-state:paused" >6nl4qf</d:slug>
                    </metadata></g>
                </svg> 
            </div>
            <div class="text-black relative flex flex-col items-center">
                <h2 class="text-3xl font-medium text-center">
                    {{__("Something went wrong")}}
                </h2>
                <br>
                <div class="text-gray-500 text-xs w-10/12 py-2 text-center md:text-base"> ,

                        {{-- {{__("Order Not Confirmed Description")}} --}}
                        {{"Please allow Location Service To be able to see Products page"}}
                </div>
            </div>
        </div>
    </main>
    @include('balde_components.footer')
@endsection
