@extends('layouts.master')

    @section('title')
        | {{__('About')}}
    @endsection
    @section('extraStyle')
        <link href="{{ asset('css/help-page.css') }}" rel="stylesheet">
    @endsection


    @section('content')
    {{-- side bar --}}
        @include('balde_components.navs.side-bar')
    {{-- top nav bar --}}
        @include('balde_components.navs.nav-bar-v1')
    {{-- main  --}}
        <main class="w-full bg-white" >
            <div class=" w-full  relative flex justify-center  " style="z-index: 0;height: 470px;">
                <img src="/images/faq.jpg" alt="banner" class="absolute w-full h-full object-cover opacity-70 z-0">
                <div class="w-full h-full bg-black"></div>
                <div class="z-10 container  absolute  pt-5 w-11/12 md:w-2/3 flex flex-col justify-center text-center   h-96 ">
                    <h2 class="text-2xl font-bold md:text-6xl text-white my-2">
                        {{__("about_banner_header")}}
                    </h2>
                    <p class="text-white text-xl font-semibold my-2 ">
                        {{__("about_banner_description")}}
                    </p>
                    <form class="bg-white h-14 p-2 rounded flex flex-row" action="/search" method="get">
                        <input
                            class="border-white px-2 rounded outline-white flex-1 border-r-2 "
                            name="search"
                            type="search"
                            id="search"
                            placeholder=" {{__("Search for markets or products")}} ..."
                            required
                            autocomplete="off"
                        >
                        <button class="w-10 text-green" type="submit"> <i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>
            <faq-help></faq-help>
        </main>
        {{-- footer--}}
        @include('balde_components.footer')
@endsection

    @section('extraJs')
        <script>
            const showArticle=article=>{
                let description=article.querySelector("#desc");
                description.classList.toggle('hidden');
                description.classList.toggle('block');
                let icon = article.querySelector("#icon");
                icon.classList.toggle('fa-plus');
                icon.classList.toggle('fa-minus');
            }
        </script>
    @endsection



