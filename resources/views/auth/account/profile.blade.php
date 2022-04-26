@auth
    @extends('layouts.master')
    @section('content')
        <!-- side bar -->
        @include('balde_components.navs.side-bar')                       
        <!-- top nav bar -->
        @include('balde_components.navs.nav-bar-v2')
        <main class="w-full min-h-screen pt-16 container px-2" >
        
            <div class="grid grid-cols-7  pt-6 pb-2">
                <div class="col-span-3 md:col-span-2 lg:col-span-1 flex flex-row  items-center">
                    <img src="{{auth()->user()->getFirstMediaUrl('avatar')}}" class="rounded-full object-cover w-36 h-36 mb-2 shadow-xl" alt="user avatar">   
                </div>
                <div class="col-span-4 md:col-span-5 lg:col-span-6 mx-2 md:mx-1 flex  flex-col justify-center text-black ">
                    <p class="text-black font-bold text-4xl md:text-7xl">
                        {{auth()->user()->name}}
                    </p>
                    <p class="text-gray-500 font-medium text-sm">
                        {{auth()->user()->email}}
                    </p> 
                    <p class="text-gray-400 font-medium text-xs italic">
                        {{ __('Acount created')}}   : {{auth()->user()->created_at->diffForHumans()}}
                    </p> 
                    <p class="text-gray-400 font-medium text-xs italic">
                        {{ __('Client acount')}} 
                    </p>    
                </div>
            </div>
            {{-- My orders --}}
            <p class="text-xl px-2 text-black font-bold">
                {{ __('My Orders')}} 
            </p>
            <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 py-6">
                @forelse (auth()->user()->orders->where("active",1) as $order)
                    @if( count($order->productOrders) !=0)
                        <div class="col-span-1">
                            <div class="card">
                                <div class="card-body flex flex-col">
                                    <div class="flex flex-row justify-between items-center">
                                        <p class="text-gray-400 font-medium italic  py-1 ">{{$order->created_at->diffForHumans()}}</p> 
                                        <p class="text-gray-400 font-medium italic py-1 ">{{$order->orderStatus->status}}</p>
                                    </div>
                                    <b>{{ __('Market')}} :</b>
                                    <div class="text-md py-1 "> 
                                        <i class="fas fa-store"></i> {{$order->market()->name}}
                                        <br>
                                        <i class="fas fa-phone"></i>{{$order->market()->phone}}
                                    </div>
                                    <div class="h-2"></div>
                                    <b> {{ __('Order')}} : {{$order->id}}</b>
                                    <p class="text-md py-1">
                                        <ul>
                                            @foreach ($order->productOrders as $productOrder)
                                                <li>
                                                    <i class="fas fa-utensils mr-2"></i>{{$productOrder->product->name}} (x{{$productOrder->quantity}}) 
                                                    @if(count($productOrder->options)!=0)
                                                        <span class="text-xs text-gray-400">({{ __('With extras')}})</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                            @if($order->payment)
                                                <li class="my-2 flex flex-row justify-between">
                                                    <div><b>{{ __('Payment in')}}:</b></div>
                                                    <div>{{$order->payment->method}}</div>
                                                </li>
                                                <li class="my-2 flex flex-row justify-between">
                                                    <div><b class=" text-lg text-green">{{ __('Total')}} </b></div>
                                                    <div class="font-bold text-lg text-green">{!! getPrice($order->payment->price) !!}</div>
                                                </li>
                                            @else
                                                <li class="my-2 text-green flex flex-row justify-between">
                                                    <div><b>{{ __('Without Payment')}}</b></div>
                                                </li>
                                            @endif
                                            
                                            @if ($order->delivery_address_id && $order->deliveryAddress)
                                                <li class="flex flex-row justify-between">
                                                    <div class="text-md py-1 truncate text-sm ">
                                                        <i class="fas fa-home"></i> {{$order->deliveryAddress->address}}
                                                    </div>
                                                </li>
                                            @else
                                                <li class="flex flex-row justify-between">
                                                    <div class="text-md py-1 truncate text-sm text-gray-500 ">
                                                        {{$order->market()->address}}
                                                    </div>
                                                </li>
                                            @endif
                                            
                                        </ul>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif 
                @empty
                    <div class="px-2 text-xl font-semibold text-black">
                        {{ __('No Order Yet')}}  
                    </div>
                @endforelse
            </div>
        </main>  
        @include('balde_components.footer')
    @endsection            
@endauth