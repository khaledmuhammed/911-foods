<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script type="text/javascript" defer>
            const google_maps_key = "<?php echo setting('google_maps_key');?>"; 
            const LANG_I18N = "<?php echo setting('language');?>"; 
        </script>
        <style>
            :root {
                --main_color:{{setting('main_color')}};
            }
        </style>
        
        {{-- fonts --}}
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
        {{-- title of the site --}}
        <title>{{ setting('app_name') }} @yield('title')</title>
        {{-- tab icon --}}
        <link rel="shortcut icon" href="{{ isset($app_logo) ? $app_logo : null}}" type="image/x-icon"/>
        {{-- bootstrap css --}}
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        {{-- tailwind css --}}
        <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
        {{-- costume style --}}
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        {{-- main style --}}
        <link href="{{ asset('css/main.css') }}" rel="stylesheet">
        {{-- extraStyle --}}
        @yield('extraStyle')
        
    </head>
    <body>
        <div id="app">
            <button type="button" id="top" class="z-50 hidden bg-green text-white text-2xl py-1 px-2 rounded-full outline-none border-none fixed bottom-2 right-5">
                <i class="fas fa-chevron-up"></i>
            </button>
            @yield('content')
            <example-component></example-component>
        </div>
        <script src="{{asset('js/app.js')}}"></script>
        <script src="{{asset('js/main.js')}}"></script>
        <script src="{{asset('js/costum-slick.js')}}"></script>
        @yield('extraJs')
        @error('search')
                <script type="application/javascript">
                    Vue.notify({
                        group: 'bar',
                        type: 'error',
                        title: "<?php echo __('Important message') ?> ",
                        text: "<?php echo __('search Error') ?> "
                    })
                </script>
        @enderror
    </body>
</html>