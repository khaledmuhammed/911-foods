<section class="bg-gray-200  py-20 flex flex-col">
    <div class="container">
        <div class="w-full text-center">
            <div class="w-full  self-center content-center flex flex-row justify-center -mt-4 mb-6">
                <p class="border-1 border-green w-44"></p>
            </div>
            <h2 class="text-black text-4xl font-bold py-1">
                {{__("popular_categories")}}
            </h2>
            <p class="text-gray-600 text-md font-normal  py-1">
                {{__("popular_categories_description")}}
            </p>
        </div>
        <div class="h-full w-full  slider">
            @foreach ($categorys as $category)
                <a href="/category/{{$category->id}}" class="p-4 nav-link hover:text-green-500 border-white">
                    <div class="relative w-full bg-white rounded shadow-md text-center ">
                        <div class="total absolute top-3 right-3 w-10 h-10 text-sm leading-10 items-center text-black bg-gray-300 rounded-full">
                           
                            +{{$category->products_count}}
                        </div>
                        <div class="flex flex-col h-52 justify-center p-4">
                            <img class="text-green self-center opacity-10 w-50" src="{{ $category->getFirstMediaUrl('image') }}" alt="">
                            <b class="mt-2 text-black">{{$category->name}}</b> <small class="text-gray-600">
                                @if($category->foods_avg_price)
                                    {{__("avg_price")}} {!! getPrice($category->foods_avg_price) !!}
                                @else
                                    {{__("avg_price_is_not_counted")}}
                                @endif
                            </small>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
