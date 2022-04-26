@extends('layouts.master')



@section('content')
    <!-- side bar -->
    @include('balde_components.navs.side-bar')                       
    <!-- top nav bar -->
    @include('balde_components.navs.nav-bar-v2')
<style>
    input{
        text-align: end;
    }
    .formContent{
    }
</style>
{{--    <main class="m-16 min-h-screen">--}}
{{--        <div class="flex flex-col justify-center items-center h-96"  >--}}
{{--            <p class="text-black text-5xl font-semibold text-center">{{__("Comming soon")}}</p>--}}
{{--        </div>--}}
{{--    </main>--}}
    <main class="w-full bg-white" >
        <div class=" w-full  relative flex justify-center  " style="z-index: 0;height: 470px;">
            <img src="/images/faq.jpg" alt="banner" class="absolute w-full h-full object-cover opacity-70 z-0">
            <div class="w-full h-full bg-black"></div>
            <div class="z-10 container  absolute  pt-5 w-11/12 md:w-2/3 flex flex-col justify-center text-center   h-96 ">
                <h2 class="text-2xl font-bold md:text-6xl text-white my-2">
                    {{__("Contact Us")}}
                </h2>
                <p class="text-white text-xl font-semibold my-2 ">
                    {{__("about_banner_description")}}
                </p>
            </div>
        </div>
                <div class="contact-1 py-4 md:py-12" style="text-align: start">
        <div class="container mx-auto px-4">
            <div class="xl:flex -mx-4">
                <div class="xl:w-10/12 xl:mx-auto px-4">

                    <div class="xl:w-4/4 mb-4" style="text-align: center">
                        <h1 class="text-3xl text-medium mb-4">{{__("Happy to Contact")}}</h1>
                        <p class="text-xl mb-2">{{__("Contact description")}}</p>
                        <p>{{__("Or Call Us at")}} <a href="tel:+12314561231" class="text-indigo-600 border-b border-transparent hover:border-indigo-600 transition-colors duration-300">+1 231 456 1231</a></p>
                    </div>
                    <div class="md:flex md:-mx-4 mt-4 md:mt-10" style="        display: flex;
        flex-direction: row-reverse;
        text-align: center;
">

                        <div class="md:w-2/3 md:px-4">
                            <div class="contact-form">
                                <div class="sm:flex sm:flex-wrap -mx-3">
                                    <div class="sm:w-1/2 px-3 mb-6">
                                        <input type="text" placeholder="{{__("Enter your full name")}}" style="text-align: center" class="border-2  rounded px-3 py-1 w-full focus:border-indigo-400 input">
                                    </div>
                                    <div class="sm:w-1/2 px-3 mb-6">
                                        <input type="text" placeholder="{{__("Subject")}}" style="text-align: center" class="border-2 rounded px-3 py-1 w-full focus:border-indigo-400 input">
                                    </div>
                                    <div class="sm:w-1/2 px-3 mb-6">
                                        <input type="text" placeholder="{{__("Enter your Email")}}" style="text-align: center" class="border-2 rounded px-3 py-1 w-full focus:border-indigo-400 input">
                                    </div>
                                    <div class="sm:w-1/2 px-3 mb-6">
                                        <input type="text" placeholder="{{__("Phone number")}}" style="text-align: center" class="border-2 rounded px-3 py-1 w-full focus:border-indigo-400 input">
                                    </div>
                                    <div class="sm:w-full px-3">
                                        <textarea name="message" id="message" cols="30" rows="4" style="text-align: center" placeholder="{{__("Message")}}" class="border-2 rounded px-3 py-1 w-full focus:border-indigo-400 input"></textarea>
                                    </div>
                                </div>
                                <div class="text-right mt-4 md:mt-12">
                                    <button class="border-2 border-indigo-600 rounded px-6 py-2 bg-green custom-btn-blue transition-colors duration-300">
                                        {{__("Submit")}}
                                        <i class="fas fa-chevron-right ml-2 text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="md:w-1/3 md:px-4 mt-10 md:mt-0">
                            <div class="bg-indigo-100 rounded py-4 px-6">
                                <h5 class="text-xl font-medium mb-3">{{__("about_banner_header")}}</h5>
                                <p class="text-gray-700 mb-4">{{__("Help description")}} <a href="mailto:" class="text-indigo-600 border-b border-transparent hover:border-indigo-600 inline-block">{{__("Email address")}}</a> {{__("Or Call Us at")}} <a href="tel:" class="text-indigo-600 border-b border-transparent hover:border-indigo-600 inline-block">+1 231 456 1231</a></p>
                                <p class="text-gray-700"><a href="help" class="text-indigo-600 border-b border-transparent hover:border-indigo-600 inline-block">{{__("About")}}</a> {{__("To Get More Info")}}</p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
    </main>
    @include('balde_components.footer')


@endsection
