<nav class="side-bar" id="side-bar" >
    <i id="cancel"> &times</i>
    <div class="top">
        <i class="fab fa-shopify text-2xl"></i>
        <span>{{ setting('app_name') }}</span>
    </div>
    <div class="links">
        <ul>
            <li><a href="/"> <i class="fas fa-home "></i> &nbsp; {{__("Home")}}</a></li>
            <li><a href="/markets"><i class="fas fa-store "></i> &nbsp; {{__("Markets")}}</a></li>
            <li><a href="/products"> <i class="fas fa-cubes "></i> &nbsp; {{__("Products")}}</a></li>
            <li><a href="/help"><i class="fas fa-info "></i> &nbsp; {{__("About")}}</a></li>
            <li>            <div class="dropdown ">
                    <button type="button" class=" w-100 bg-gray-200 py-1 px-2 -mt-2 text-black rounded-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
            @guest
                <li><a href="/login"><i class="icon fas fa-sign-in-alt"></i> &nbsp; {{__("Login")}} </a></li>
            @else
                <li><a href="/wishlist"><i class="icon far fa-heart"></i> &nbsp; {{__("Favorites")}}  </a></li>
                <li><a href="/my-account"><i class="icon far fa-user"></i> &nbsp; {{__("My account")}} </a></li>
                <li><a  href="{{ route('logout') }}" 
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                    ><i class="icon fas fa-door-open"></i> &nbsp;{{__("Logout")}} </a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                </form>
           @endguest

            {{-- <li>
                <a class="drop_down_btn" href="#">pages <i class="fas fa-caret-down down"></i> </a>
                <ul>
                    <li> <a href="./index.html">index</a></li>
                    <li> <a href="./confirm-delivery.html">confirm</a></li>
                    <li> <a href="./grid-listing-filterscol-map.html">grid</a></li>
                <li> <a href="#">page4</a></li>
                </ul>
            </li> --}}

        </ul>
    </div>
</nav>
