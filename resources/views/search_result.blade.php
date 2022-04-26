@extends('layouts.master')
@section('extraStyle')
<link href="{{ asset('css/checkbox-radio-input.css') }}" rel="stylesheet">
@endsection
@section('content')
    <!-- side bar -->
    @include('balde_components.navs.side-bar')
    <!-- top nav bar -->
    @include('balde_components.navs.nav-bar-v2')
    
    <main class="w-full mt-16 mb-5" >
        <header class="py-3  w-full md:h-24  bg-gray-100 ">
            <div class="container flex flex-col md:flex-row md:justify-between items-center ">
                    <div class="hidden md:block">
                        <div class="text-black text-sm py-1 ">
                            <a href="/" class="hover:text-gray-800 hover:no-underline">
                                {{__("Home")}}
                            </a> &nbsp; > &nbsp;
                            <a href="#markets" class="text-green hover:no-underline">
                                {{__("Markets")}}
                            </a> |
                            <a href="#products" class="text-green hover:no-underline">
                                {{__("Products")}}
                            </a>
                        </div>
                        <p class="text-black font-medium text-lg py-1">
                            {{__('Search for markets or products')}}
                        </p>
                    </div>

                    <form action="/search" method="get"  class=" w-full h-12 md:w-2/5 md:h-4/6 bg-white flex flex-row rounded ">
                        {{-- @csrf --}}
                        <input id="search" type="search"  name="search"
                            class="flex-1 bg-white outline-none p-3  rounded-l-sm"
                            placeholder="{{__('Search for markets or products')}} ..."
                            autocomplete="off"
                            required
                        >
                        <button type="submit" class="w-14 bg-green rounded-r-sm text-white"><i class="fas fa-search"></i></button>
                    </form>
            </div>
        </header>
        <!-- markets -->
        <div class="container w-full flex flex-col pt-4 ">
                <span class="w-40 h-1 bg-green"></span>
                <div class="flex items-center justify-between">
                    <h2 class="text-black font-bold text-4xl pt-4 pb-2">
                        {{__("Markets")}}
                    </h2>
                    <a id="markets" href="#markets" class="nav-link text-white align-middle text-center bg-green rounded-3xl py-2 px-4 leading-6">
                        {{__("Total")}} {{count($markets)}}
                    </a>
                </div>
                <p class="text-gray-400">
                    @if($field)
                        {{__('Market belong to field')}} "{{$field->name}}"
                    @else
                       {{__('Search result on markets with a name like')}} " {{$search_value}} "
                    @endif
                </p>
        </div>
        <section class="container py-4 w-full flex flex-col lg:flex-row ">
            <div class="w-full mt-3">
                    <div class="grid gap-5 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4  -mt-1 ">
                       @forelse ($markets as $market)
                            <div onclick="goToMarket({{$market->id}})"  class="box h-64 mt-1 w-full rounded">
                                <div class="h-2/3 w-full relative cursor-pointer">
                                    @if($field)
                                        <span class="absolute text-white rounded top-3 left-3  text-sm font-semibold  py-1 px-2 " style="background-color: #333;" >
                                            {{$field->name}}    
                                        </span>
                                    @else
                                        @if(count($market->fields)!=0)
                                            <span class="absolute text-white rounded top-3 left-3  text-sm font-semibold  py-1 px-2 " style="background-color: #333;" >
                                                {{$market->fields->first()->name}}
                                            </span>    
                                        @endif
                                    @endif
                                    @if ($market->closed)
                                        <span class="absolute text-white rounded top-3 right-3 text-sm font-semibold  py-1 px-2 bg-red-600 ">
                                            closed
                                        </span>
                                    @endif
                                    
                                    @if ($market->getFirstMediaUrl('image') != "")
                                        <img src="{{$market->getFirstMediaUrl('image')}}" alt="market" class="h-full w-full rounded ">
                                    @else
                                        <img src="/images/restaurant-placeholder.png" alt="market" class="h-full w-full rounded ">
                                    @endif

                                    <div class="absolute w-full overflow-hidden px-3 py-2.5 bottom-0 flex flex-col justify-center rounded-b-sm" style="background: rgba(92, 92, 92, 0.315);">
                                        <h2 class="text-white text-xl font-semibold truncate">
                                            {{$market->name}}
                                        </h2>
                                        <p class="text-gray-100 text-xs truncate">
                                            {{$market->address}}
                                        </p>
                                    </div>
                                </div>
                                <div class="px-1 py-3 flex flex-row justify-between text-sm w-full">
                                    <span class="text-gray-400 flex-1 h-20 overflow-hidden">
                                        {!! $market->description !!}
                                    </span>
                                    <div class="w-5/12 flex flex-row justify-end ">
                                        <div class="flex flex-col">
                                            <div class="text-gold flex-row flex justify-end items-center ">
                                                <div class="bg-gray-200 py-1 px-2 rounded">
                                                    <span class="text-gray-700 font-semibold">
                                                        {{$market->rate}}
                                                    </span>
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            </div>
                                            <div class="flex-row  flex justify-end items-center mt-1 @if ($market->available_for_delivery) text-gray-600 @else line-through text-gray-400 @endif">
                                                {{__("Delivery")}}
                                                <i class="fas fa-motorcycle ml-1"></i>
                                            </div>
{{--                                            <div class="flex-row flex justify-end items-center mt-1 @if ($market->closed) line-through text-gray-400 @else text-gray-600 @endif">--}}
{{--                                               {{__("Take away")}}--}}
{{--                                                <i class="fas fa-shopping-basket ml-1"></i>--}}
{{--                                            </div>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                       @empty
                            <div class="text-center font-bold text-3xl col-span-4 h-24 flex flex-row justify-center items-center">
                                {{__('No market is found')}}
                            </div>
                       @endforelse
                    </div>
            </div>
        </section>
        {{-- products --}}
        <div class="container w-full flex flex-col pt-4 ">
            <span class="w-40 h-1 bg-green"></span>
            <div class="flex items-center justify-between">
                <h2 class="text-black font-bold text-4xl pt-4 pb-2">
                    {{__("Products")}}
                </h2>
                <a id="products" href="#products" class="nav-link text-white align-middle text-center bg-green rounded-3xl py-2 px-4 leading-6">
                    {{__("Total")}} {{count($products)}}
                </a>
            </div>
            <p class="text-gray-400">
                @if($category)
                    {{__('Products belong to category')}} "{{$category->name}}"
                @else
                   {{__('Search result on products with a name like')}} " {{$search_value}} "
                @endif
            </p>
        </div>
        <section class="container py-4 w-full flex flex-col lg:flex-row ">
            <!-- gallery -->
            <div class="w-full mt-3">
                    <div class="grid gap-5 grid-cols-1 md:grid-cols-2 ">
                    @forelse ($products as $product)

                    <div onclick="goToProduct({{$product->market->id}})" class="cursor-pointer box h-44 w-full flex" >
                            <div class="h-full w-1/3">
                        
                                @if ($product->getFirstMediaUrl('image') != "")
                                    <img src="{{$product->getFirstMediaUrl('image')}}" alt="product image" class="h-full w-full rounded-l-md object-cover">
                                @else
                                    <img src='/images/food-placeholder.jpeg' alt="product image" class="h-full w-full rounded-l-md object-cover">
                                @endif
                            </div>
                            <div class="flex-1 relative flex flex-col justify-between bg-gray-50 rounded-r-md px-4 pt-4 pb-3">
                                <div class="flex w-full justify-between align-items-start">
                                    <div>
                                        <span class="text-gray-800 ">
                                            {{$product->category->name}}
                                        </span>
                                        <h2 class="text-black text-2xl font-bold ">
                                            {{$product->name}}
                                        </h2>
                                        <p class="text-gray-400 text-sm">{{$product->market->name}}</p>
                                        <p class="text-gray-400 text-xs">{{$product->market->address}}</p>
                                    </div>
                                    @if($product->rate)
                                        <div class="bg-gray-200 py-1 px-2 rounded">
                                            <span class="text-gray-700 font-semibold">
                                                {{$product->rate}}
                                            </span>
                                            <i class="text-gold fas fa-star"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex w-full justify-between align-items-baseline">
                                    <div class="flex flex-row items-center">
                                        <p class="text-green text-base font-bold sm py-1 px-2">
                                            {!! getPrice($product->getPrice()) !!}
                                        </p>
                                        @if ($product->discount_price !=0)
                                            <span class="bg-red-600 text-white rounded text-sm py-1 px-2">
                                                -{{number_format(100-($product->discount_price * 100 / $product->price),0)}} %
                                            </span>
                                        @endif
                                    </div>


                                    <div class="text-sm">
                                        <span class="px-1 @if (! $product->market->available_for_delivery) line-through text-gray-400 @else text-gray-600 @endif" >
                                            {{__("Delivery")}}
                                            <i class="fas fa-motorcycle"></i>
                                        </span>

                                        <span class="px-1 @if ($product->market->closed) line-through text-gray-400 @else text-gray-600 @endif" >
                                            {{__("Take away")}}
                                            <i class="fas fa-shopping-basket"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                            <div class="text-center font-bold text-3xl col-span-4 h-24 flex flex-row justify-center items-center">
                                {{__('No product is found')}}
                            </div>
                    @endforelse
                    </div>
            </div>
        </section>
    </main>
    {{-- footer--}}
    @include('balde_components.footer')
@endsection
@section('extraJs')
    <script type="application/javascript">
        function goToProduct(market_id){
            location.href=window.location.origin+'/markets/'+market_id+'#products';
        }
        function  goToMarket(market_id){
            location.href=window.location.origin+'/markets/'+market_id;
        }
    </script>
@endsection

