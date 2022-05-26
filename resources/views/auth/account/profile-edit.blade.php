@auth
    @extends('layouts.master')
    @section('content')
        <!-- side bar -->
        @include('balde_components.navs.side-bar')
        <!-- top nav bar -->
        @include('balde_components.navs.nav-bar-v2')
        <main class="w-full min-h-screen pt-16 container px-2">
            <div class="container w-full flex flex-col pt-4 "><span class="w-40 h-1 bg-green"></span>
                <div class="flex items-center justify-between">
                    <h2 class="text-black font-bold text-4xl pt-3 pb-2">
                        {{ __('My account') }}
                </div>
                <p class="text-gray-400">
                    Edit Your Informations
                </p>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(Session::has('message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }}" role="alert">
                        <p>{{ Session::get('message') }}</p>
                    </div>
                @endif

                <div class="md:flex md:-mx-4 mt-4 md:mt-10"
                    style="display: flex;flex-direction: row-reverse;text-align: center;">
                    <div class="md:w-2/3 md:px-4" style="margin-top: -3rem;">
                        <div class="contact-form">
                            <form class="mt-4" method="POST" action="/my-account/update"  enctype="multipart/form-data">
                                @csrf
                                <div class="sm:flex sm:flex-wrap -mx-3">
                                    <div class="sm:w-1/2 px-3 mb-6">
                                        <label for="name">Name</label>
                                        <input id="name" name="name" type="text" value="{{ auth()->user()->name }}"
                                            style="text-align: center"
                                            class="border-2  rounded px-3 py-1 w-full focus:border-indigo-400 input">
                                    </div>
                                    <div class="sm:w-1/2 px-3 mb-6">
                                        <label for="email">Email</label>
                                        <input id="email" name="email" type="text" value="{{ auth()->user()->email }}"
                                            style="text-align: center"
                                            class="border-2 rounded px-3 py-1 w-full focus:border-indigo-400 input">
                                    </div>
                                    <div class="sm:w-1/2 px-3 mb-6">
                                        <label for="password">Password</label>
                                        <input id="password" name="password" type="password" placeholder="Password"
                                            style="text-align: center" 
                                            class="border-2 rounded px-3 py-1 w-full focus:border-indigo-400 input">
                                    </div>
                                    <div class="sm:w-1/2 px-3 mb-6">
                                        <label for="password_confirm">Confirm Password</label>
                                        <input id="password_confirm" name="password_confirm" type="password"
                                            placeholder="Password Confirm" style="text-align: center"
                                            class="border-2 rounded px-3 py-1 w-full focus:border-indigo-400 input">
                                    </div>
                                    <div class="sm:w-full px-3">
                                        <label for="name">Avatar</label>
                                        <input id="u_image" name="u_image" type="file"
                                            style="text-align: center"
                                            class="border-2  rounded px-3 py-1 w-full focus:border-indigo-400 input">
                                            {{-- @if (auth()->user()->hero_image)
                                                <img src="{{auth()->user()->hero_image}}" alt="user avatar">
                                            @endif --}}
                                    </div>
                                    @if ("/storage/images/{{ auth()->user()->hero_image }}")
                                        <img src="{{ auth()->user()->hero_image }}">
                                    @else
                                            <p>Image Not Found</p>
                                    @endif
                                </div>
                                <div class="text-right mt-4 md:mt-12">
                                    <button
                                        class="border-2 border-indigo-600 rounded px-6 py-2 bg-green custom-btn-blue transition-colors duration-300">
                                        {{ __('Edit') }}
                                        <i class="fas fa-chevron-right ml-2 text-sm"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="grid grid-cols-7  pt-6 pb-2">
                <div class="col-span-3 md:col-span-2 lg:col-span-1 flex flex-row  items-center">
                    <img src="{{ auth()->user()->getFirstMediaUrl('avatar') }}"
                        class="rounded-full object-cover w-36 h-36 mb-2 shadow-xl" alt="user avatar">
                </div>


            </div> --}}
            {{-- My account --}}
            {{-- <p class="ml-4 text-xl px-2 text-black font-bold">
                {{ __('My account') }}
            </p> --}}


        </main>
        @include('balde_components.footer')
    @endsection
@endauth

{{-- <script>
    document.getElementById("password").innerHTML = "";
</script> --}}