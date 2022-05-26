<!-- navbar-brand -->
<div >
    <button id="show-side-bar" class="md:hidden nav-link absolute left-4 top-2 w-10 h-10 outline-none text-lg border-none"><i class="fas fa-align-left"></i></button>
    {{-- <a class="navbar-brand font-semibold hover:text-gray-900" href="/">{{ setting('app_name') }} </a> --}}
    <a class="navbar-brand font-semibold hover:text-gray-900" href="/">
        <img src="{{ isset($app_logo) ? $app_logo : null}}" alt="911-Logo" style=" height: 85px;width: 90px;margin-top: 25px;">
    </a>
    @guest
        <a class="nav-link md:hidden absolute right-6 top-3 w-10 h-10 text-lg" href="/login">
            <i class="fas fa-sign-in-alt"></i>
        </a>
    @else
        <div class="btn-group absolute right-6 top-4  text-sm flex md:hidden">
            <button type="button" id="triggerId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{auth()->user()->getFirstMediaUrl('avatar')}}" class="rounded-full object-cover w-8 h-8 shadow-xl" alt="user">   
            </button>
            <div class="dropdown-menu dropdown-menu-left -ml-28 mt-3" aria-labelledby="triggerId">
                <a class="dropdown-item text-black p-2"  href="/my-account" >
                    <i class="fas fa-user" ></i> <span>{{__("My account")}}</span>
                </a>
                <a class="dropdown-item text-red-500 hover:text-red-400 hover:bg-gray-50 p-2" 
                    href="{{ route('logout') }}" 
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="fas fa-door-open" ></i> <span>Logout</span>
                </a>
            </div>
        </div> 
    @endguest

</div>
<!-- links -->
<div class="hidden md:flex md:flex-row">
    <ul class="navbar-nav flex flex-row mr-auto ">
        <li class="nav-item ml-3">
            <a class="nav-link " aria-current="page" href="/"> 
                <i class="fas fa-home mr-1   "></i>           
                {{__("Home")}}
            </a>
        </li>
        <li class="nav-item ml-3">
            <a class="nav-link " aria-current="page" href="/markets">
                <i class="fas fa-store mr-1   "></i>
                {{__("Markets")}}
            </a>
        </li>
        <li class="nav-item ml-3">
            <a class="nav-link " aria-current="page" href="/products">
                <i class="fas fa-cubes mr-1   "></i>
                {{__("Products")}}
            </a>
        </li>
        <li class="nav-item ml-3">
            <a class="nav-link " aria-current="page" href="/help">
                <i class="fa fa-question-circle mr-1" aria-hidden="true"></i>
                {{__("About")}}
            </a>
        </li>
        <li class="nav-item ml-3 flex flex-col flex-wrap  py0  my-2">
            <div class="dropdown ">
                <button type="button" class="bg-gray-200 py-1 px-2 -mt-2 text-black rounded-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-globe "></i>   {{__("Language")}}
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a id="en" onclick="setLanguage(this.id)" class="dropdown-item cursor-pointer flex flex-row items-center" >
                        <img width="30" height="30" src="/images/flags/uk_flag.png" alt="flag" class="mr-2 rounded-sm">
                        <span>{{__("English")}}</span>
                    </a>
                    <a id="ar" onclick="setLanguage(this.id)" class="dropdown-item cursor-pointer flex flex-row items-center" >
                        <img width="30" height="30" src="/images/flags/ar_flag.png" alt="flag" class="mr-2 rounded-sm">
                        <span>{{__("Arabic")}}</span>
                    </a>

                </div>
            </div>
            <script type="application/javascript">
                function setLanguage(lang) {
                    loadLanguageAsync(lang);
                }
            </script>
        </li>
        {{-- <li class="nav-item dropdown ml-3">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Pages
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item mt-2 p-2.5 text-black " href="/confirm">Confirm</a>
            </div>
        </li> --}}
        @guest
            <li class="nav-item ml-3">
                <a class="nav-link"  href="/login">
                    <i class=" icon fas fa-sign-in-alt mr-1"></i>
                    {{__("My account")}}</a>
            </li>
        @else
        <li class="nav-item ml-3">
            <a class="nav-link" href="/wishlist"><i class="icon far fa-heart"></i></a>
        </li>
        <li class="nav-item ml-3  flex flex-row items-center">
            <div class="btn-group ml-3">
                <button class="dropdown-toggle" type="button" id="triggerId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{auth()->user()->name}}
                </button>
                <div class="dropdown-menu dropdown-menu-left -ml-20 mt-3" aria-labelledby="triggerId">
                    <a class="dropdown-item text-black p-2"  href="/my-account" >
                        <i class="fas fa-user" ></i> <span>{{__("My account")}}</span>
                    </a>
                    <a class="dropdown-item text-red-500 hover:text-red-400 hover:bg-gray-50 p-2" 
                        href="{{ route('logout') }}" 
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="fas fa-door-open" ></i> <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>    
        </li>
        @endguest
    </ul>
</div>
