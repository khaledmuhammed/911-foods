@extends('layouts.master')

@section('content')
    <!-- side bar -->
    @include('balde_components.navs.side-bar')     
    <!-- top nav bar -->
    @include('balde_components.navs.nav-bar-v1')
    {{-- banner  --}}
    <banner></banner>
    @include('balde_components.popular-categories')
    
    <main-index :app_name="`{{setting('app_name')}}`" :countMarket={{$countMarket}} ></main-index>

    <footer class="h-96 relative" >
        <img src="/images/bg3.jpg" alt="delivery" class="w-full h-full absolute top-0 left-0 object-cover">
        <div style="background: rgba(0, 0, 0, 0.281);" class="rounded w-full h-full absolute ">
            <div class="row">
                <div class="col-md-6 col-sm-12">
        <div class="relative   p-4 top-1/4 w-75 h-auto m-auto  bg-black rounded  ">
            <h2 class="text-white font-bold text-xl  md:text-3xl ">
                {{ __('Are you a Rider') }}
            </h2>
            <p class="text-md text-gray-200 w-5/6">
                {{__("indexPage_footer_banner_description")}}
            </p>
            <br>
            <a href="/contact-us" class="btn bg-green custom-btn-blue md:w-1/3 ">
                {{ __('Contact Us') }} 
            </a>
        </div></div>
                <div class="col-md-6 col-sm-12">
                    <div class="relative m-auto w-75 h-auto  p-4 top-1/4  bg-black rounded ">
                <h2 class="text-white font-bold text-xl  md:text-3xl ">
                    {{ __('Are you a vendor') }}
                </h2>
                <p class="text-md text-gray-200 w-5/6">
                    {{__("indexPage_footer_banner_description")}}
                </p>
                <br>
                <a href="/contact-us" class="btn bg-green custom-btn-blue md:w-1/3 ">
                    {{ __('Contact Us') }}
                </a>
            </div></div>
            </div>
        </div>
    </footer>
    @include('balde_components.footer')
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

    document.getElemetById('')
</script>
