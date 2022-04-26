@extends('layouts.master')
@section('content')

    @section('title')
        @yield('title')
    @endsection

    <!-- side bar -->
    @include('balde_components.navs.side-bar')                       
    <!-- top nav bar -->
    @include('balde_components.navs.nav-bar-v2')

    <div class="container">
        <div class="flex flex-col justify-center items-center h-screen">
            <div class="text-green font-extrabold text-9xl font-mono my-2">
                @yield('code')
            </div>
            <div class="text-black font-semibold text-lg text-center my-2">
                @yield('message')
            </div>
            <div class="flex flex-row justify-between w-80  my-2" >
                <a href="/" class="nav-link rounded bg-green  h-12 text-white p-3 flex flex-row items-center justify-center text-center">
                    <i class="fas fa-home mx-2 " aria-hidden="true"></i>
                    {{__("Home")}}
                </a>
                <a href="/contact" class="nav-link rounded border-green h-12 text-green p-3  border-1 flex flex-row items-center justify-center text-center">
                    <i class="fas fa-envelope mx-2 " aria-hidden="true"></i>
                    {{__("Contact Us")}}
                </a>
            </div>
        </div>
    </div>
    @include('balde_components.footer')
@endsection
