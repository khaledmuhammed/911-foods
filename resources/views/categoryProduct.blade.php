
@extends('layouts.master')


@section('extraStyle')
<link href="{{ asset('css/checkbox-radio-input.css') }}" rel="stylesheet">
@endsection

@section('content')
    <!-- side bar -->
    @include('balde_components.navs.side-bar')
    <!-- top nav bar -->
    @include('balde_components.navs.nav-bar-v2')
    <main class="w-full mt-16" >
        <header class="py-3  w-full md:h-24  bg-gray-100 ">
            <div class="container flex flex-col md:flex-row md:justify-between items-center ">
                    <div class="hidden md:block">
                        <div class="text-black text-sm py-1 ">
                            <a href="/" class="hover:text-gray-800 hover:no-underline">
                                {{__("Home")}}
                            </a> &nbsp; > &nbsp;
                            <a href="#categorys" class="text-gray-800  hover:text-gray-800  hover:no-underline">
                                {{__("Categorys")}}
                            </a> &nbsp; > &nbsp;
                            <a href="/category/{{$category->id}}" class="text-green hover:text-green-400 hover:no-underline">
                                {{$category->name}}
                            </a>
                        </div>
                        <p class="text-black font-medium text-lg py-1">
                            {{__('Search for Markets or Products')}}
                        </p>
                    </div>

                    <form action="/search" method="get"  class=" w-full h-12 md:w-2/5 md:h-4/6 bg-white flex flex-row rounded ">
                        {{-- @csrf --}}
                        <input id="search" type="search"  name="search"
                            class="flex-1 bg-white outline-none p-3  rounded-l-sm"
                            placeholder="{{__('Searchfor Markets or Products')}} ..."
                            autocomplete="off"
                            required
                        >
                        <button type="submit" class="w-14 bg-green rounded-r-sm text-white"><i class="fas fa-search"></i></button>
                    </form>
            </div>
        </header>

        {{-- Product --}}
        <div class="container w-full flex flex-col pt-4 ">
            <span class="w-40 h-1 bg-green"></span>
            <div class="flex items-center justify-between">
                <h2 class="text-black font-bold text-4xl pt-4 pb-2">
                    {{__("Products")}}
                </h2>
                <a id="products" href="#categorys" class="btn custom-btn-blue bg-green rounded-3xl py-2 px-4 leading-6">
                    {{__("Total")}} {{count($products)}}
                </a>
            </div>
            <p class="text-gray-400">
                {{__('Products belong to category')}} "{{$category->name}}"
            </p>
        </div>
        <section class="container py-4 w-full flex flex-col lg:flex-row ">
            <!-- gallery -->
            <div class="w-full mt-3">
                <div class="grid gap-5 grid-cols-1 md:grid-cols-2 ">
                    @forelse ($products as $product)
                        <div  onclick="goToProduct({{$product->market->id}})" class="cursor-pointer box h-44 w-full flex" >
                            <div class="h-full w-1/3">
                                @if ($product->getFirstMediaUrl('image') != "")
                                    <img src="{{$product->getFirstMediaUrl('image')}}" alt="image" class="h-full w-full rounded-l-md object-cover">
                                @else
                                    <img src='/images/food-placeholder.jpeg' alt="image" class="h-full w-full rounded-l-md object-cover">
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
                                        <span class="px-1 @if (!$product->market->available_for_delivery || $product->market->closed) line-through text-gray-400 @else text-gray-600 @endif" >
                                            {{__("Delivery")}}
                                            <i class="fas fa-motorcycle"></i>
                                        </span>

{{--                                        <span class="px-1 @if ($product->market->closed) line-through text-gray-400 @else text-gray-600 @endif" >--}}
{{--                                            {{__("Take away")}}--}}
{{--                                            <i class="fas fa-shopping-basket"></i>--}}
{{--                                        </span>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                            <div class="text-center font-bold text-3xl col-span-4 h-24 flex flex-row justify-center items-center">
                                {{__('No Products is found')}}
                            </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- pagination --}}
        <nav aria-label="Page navigation example" class="py-4">
            <ul class="pagination justify-content-center">
                <li class="page-item @if ($products->onFirstPage()) disabled @endif">
                    <a class="page-link text-green hover:text-green-400" href="{{$products->previousPageUrl()}}" tabindex="-1" aria-disabled="true">
                        {{__("Previous")}}
                    </a>
                </li>
                <li class="page-item ">
                    <p class="page-link cursor-default text-black hover:text-gray-600 hover:bg-transparent ">
                        {{__("Page")}} {{$products->currentPage()}}
                        {{__("of")}} {{$products->lastPage()}}
                    </p>
                </li>

                <li class="page-item  @if ($products->currentPage() === $products->lastPage()) disabled @endif">
                    <a class="page-link text-green hover:text-green-400 " href="{{$products->nextPageUrl()}}">
                        {{__("Next")}}
                    </a>
                </li>
            </ul>
        </nav>
    </main>
    {{-- footer--}}
    @include('balde_components.footer')
@endsection
@section('extraJs')
<script type="application/javascript">
    function goToProduct(market_id){
        location.href=window.location.origin+'/markets/'+market_id+'#products';
    }
</script>
@endsection
