@extends('layouts.master')

@section('extraStyle')
    <link href="{{ asset('css/confirm-delivery.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sign-in.css') }}" rel="stylesheet">
    
@endsection

@section('content')
    <!-- side bar -->
    @include('balde_components.navs.side-bar')                       
    <!-- top nav bar -->
    @include('balde_components.navs.nav-bar-v2')
    
    <main class="w-full mt-16 mb-2 flex flex-row justify-center" >
        <div class="container w-4/5 md:w-3/5 lg:w-1/3 mt-3 shadow-2xl p-3  bg-white" style="height:fit-content;" >
            <ul class="nav nav-pills w-full flex flex-row justify-between" id="myTab" role="tablist">
                <li class="nav-item flex-1" role="presentation">
                    <a class="nav-link active text-center mr-2 hover:bg-gray-100 " id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">
                      Login
                    </a>
                </li>
                <li class="nav-item flex-1" role="presentation">
                  <a class="nav-link text-center ml-2 hover:bg-gray-100" id="sign-tab" data-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="false">Sign in</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active my-2" id="login" role="tabpanel" aria-labelledby="login-tab">
                    @include('auth.forms.login')
                </div>
                <div class="tab-pane fade my-2" id="register" role="tabpanel" aria-labelledby="register-tab">
                    @include('auth.forms.register')
                </div>
            </div>                                           
        </div>
	</main>
    @include('balde_components.footer')    
@endsection

	
    