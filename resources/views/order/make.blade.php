    @extends('layouts.master')
    @section('extraStyle')    
        <link href="{{ asset('css/detail-restaurant-tabs.css') }}" rel="stylesheet">
        <style>
            main::after {
            content: "";
            background: url("/images/bg.png");
            opacity: 0.3;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            position: absolute;
            z-index: -1;
            }
        </style>
    @endsection   
    @section('content')
        @include('balde_components.navs.side-bar')                       
        @include('balde_components.navs.nav-bar-v2')
        <main class="w-full mt-16  relative  " >
            <form name="form_sub" method="post" action="{{route("order")}}" class="container flex flex-col lg:flex-row py-10">
                @csrf
                {{-- form inputs --}}
                <div class="flex-1  lg:mr-5">
                    {{-- Personal Details --}}
                    <div class="w-full rounded mb-3 shadow-lg">
                        <header class="w-full bg-green text-white text-xl rounded-t-sm py-4 px-3 font-bold text-left">
                        {{__('Personal Details')}}
                        </header>
                        <div class="bg-white grid grid-cols-6 gap-6  p-4 rounded-b-sm" >
                            <!-- First name -->
                                <div class="col-span-6  ">
                                    <label for="first_name" class="block text-sm font-medium text-gray-700">{{__('Full name')}}</label>
                                    <input  type="text" name="first_name" value="{{auth()->user()->name}}"  id="first_name" required readonly class="mt-1 p-2 outline-none  block w-full shadow-sm sm:text-sm rounded bg-gray-100 text-gray-500" >
                                </div>
                            <!-- email -->
                                <div class="col-span-6 md:col-span-3">
                                        <label for="email" class="block text-sm font-medium text-gray-700">{{__('Email address')}}</label>
                                        <input type="email" name="email" value="{{auth()->user()->email}}"id="email" required readonly class="mt-1 p-2 outline-none  block w-full shadow-sm sm:text-sm rounded bg-gray-100 text-gray-500" >
                                </div>
                            <!-- phone -->
                                @if(isset($customFields["phone"]))
                                    <div class="col-span-6 md:col-span-3   ">
                                        <label for="phone" class="block text-sm font-medium text-gray-700"> 
                                            {{__('Phone number')}}
                                        </label>
                                        <input type="text" name="phone" id="phone" autocomplete="phone" placeholder="{{$customFields["phone"]["view"]}}" required  readonly
                                            value="@if(old('phone')){{ old('phone')}} @else{{$customFields["phone"]["value"]}}@endif"   
                                            class="mt-1 p-2 outline-none bg-gray-100 text-gray-500 block w-full shadow-sm sm:text-sm rounded  @error('phone') form-control is-invalid  @enderror"    >
                                        @error('phone')
                                            <div class="text-red-500 text-sm ">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="phone_type" value="default">
                                @else
                                    <div class="col-span-6 md:col-span-3   ">
                                        <label for="phone" class="block text-sm font-medium text-gray-700"> 
                                            {{__('Phone number')}}
                                        </label>
                                        <input 
                                            type="text" 
                                            name="phone" 
                                            id="phone" 
                                            autocomplete="phone" 
                                            placeholder="{{__('Phone number')}} ..."
                                            required  
                                            value="{{ old('phone') }}"   
                                            class="mt-1 p-2 outline-none  block w-full shadow-sm sm:text-sm rounded  @error('phone') form-control is-invalid  @enderror"    
                                        >
                                        @error('phone')
                                            <div class="text-red-500 text-sm ">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="phone_type" value="costume">
                                @endif
                            
                            {{-- address --}}
                                <div id="address" class="col-span-6 grid grid-cols-6 gap-6 " >
                                        @if (isset($customFields["address"]))
                                            <div class="col-span-5">
                                                <label for="address" class="block text-sm font-medium text-gray-700">
                                                    {{__('Delivery Addres')}} 
                                                </label>
                                                    <select id="address" name="address" required class="mt-1 p-2 outline-none  block w-full shadow-sm sm:text-sm rounded"  >
                                                        @foreach ($customFields["address"] as $address)
                                                            <option value="{{$address["value"]}}" selected >{{$address["view"]}}</option>
                                                        @endforeach                                        
                                                    </select>
                                            </div>
                                            <div class="col-span-1 flex flex-row items-center justify-center ">
                                                <button class=" text-xs md:text-base py-2 bg-green text-white rounded  w-36 mt-4 px-2" type="button" onclick="setNewAddress()"> 
                                                    {{__('New Addres')}}  
                                                </button>
                                            </div>
                                            <input type="hidden" name="address_type" value="default">
                                        @else
                                            <div class="col-span-6 md:col-span-4">
                                                <label for="city" class="block text-sm font-medium text-gray-700">
                                                    {{__('Delivery Addres')}} 
                                                </label>
                                                <input 
                                                    type="text" 
                                                    name="address" 
                                                    id="address"  
                                                    placeholder="...." 
                                                    autocomplete="address"
                                                    required
                                                    value="{{ old('address') }}" 
                                                    class="mt-1 p-2 outline-none  block w-full shadow-sm sm:text-sm rounded  @error('address') form-control is-invalid  @enderror "
                                                >
                                                @error('address')
                                                    <div class="text-red-500 text-sm ">{{ $message }}</div>
                                                @enderror
                                            </div>   
                                            <div class="col-span-6 md:col-span-2">
                                                <label for="street_address" class="block text-sm font-medium text-gray-700">
                                                    {{__('Delivery Addres description')}}  
                                                </label>
                                                <input 
                                                    type="text" 
                                                    name="delivery_address_description" 
                                                    id="delivery_address_description" 
                                                    autocomplete="delivery_address_description"  
                                                    placeholder="example : Home"
                                                    required 
                                                    value="{{ old('street_address') }}" 
                                                    class="mt-1 p-2 outline-none  block w-full shadow-sm sm:text-sm rounded  @error('delivery_address_description') form-control is-invalid  @enderror " 
                                                >
                                                @error('delivery_address_description')
                                                        <div class="text-red-500 text-sm ">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <input type="hidden" name="address_type" value="costume">
                                        @endif
                                </div>
                            {{-- hint --}}
                                <div class="col-span-6">
                                    <label for="hint" class="block text-sm font-medium text-gray-700">
                                        {{__("Hint")}}
                                    </label>
                                    <textarea name="hint" id="hint" cols="10" rows="4" 
                                        style="border: 1px solid #eee" class="mt-1 p-3 outline-none block w-full shadow-md sm:text-sm rounded" >
                                        {{old("hint")}}
                                    </textarea>
                                </div>                                
                        </div>
                    </div>
                    {{-- Payment Method --}}
                    <div class="w-full rounded my-3 shadow-lg">
                        <header class="w-full bg-green text-white text-xl rounded-t-sm py-4 px-3 font-bold text-left">
                            {{__('Payment Method')}}
                        </header>
                        <div class="bg-white p-4 rounded-b-sm">
                            <ul id="payment-tabs" class="nav nav-pills mb-3 flex flex-col md:flex-row" id="pills-tab" role="tablist">
                                <li id="card_li" class="nav-item flex-1 text-md" role="presentation">
                                    <a class="nav-link flex justify-between items-center" onclick="setMethod('card')" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true" >
                                        {{__('Pay with Paymob')}}
                                        <i class="fas fa-wallet text-lg ml-2"></i>
                                    </a>
                                </li>

                                <li id="cash_li" class="nav-item  flex-1 text-md" role="presentation">
                                    <a class="nav-link active flex justify-between items-center" onclick="setMethod('cash')" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="true" >
                                        {{__('Pay with Cash')}}
                                        <i class="far fa-credit-card text-lg"></i>
                                    </a>
                                </li>
                                    
                            </ul>
                            {{-- <div class="tab-content" id="pills-tabContent"> --}}
                                {{-- <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                                    <p class=" text-gray-900 text-3xl my-7">
                                        {{__('Pay with Cash')}}
                                    </p>
                                </div>
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                    <p class=" text-gray-900 text-3xl my-7">
                                        {{__('Pay with Credit card')}}
                                    </p>
                                </div> --}}
                                {{-- <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                    <p class=" text-gray-900 text-3xl my-7">
                                        {{__('Comming soon')}}
                                    </p>
                                </div>
                                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                                    <p class=" text-gray-900 text-3xl my-7">
                                        {{__('Comming soon')}}
                                    </p>
                                </div> --}}
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
                {{-- Order Summary --}}
                <div class="w-full lg:w-1/3 rounded ">
                    <div class=" w-full border-2 bg-white rounded  border-gray-300 border-dotted">
                        <div class="text-center border-b-2 bg-gray-100 border-gray-300 border-dotted py-3">
                            <p class="text-2xl font-bold">{{__('Order Summary')}}</p>
                            <p class="text-gray-900 font-medium "> {{$market->name}}</p>
                        </div>
                        <div class="py-3 px-4 flex flex-col">  
                            <!-- Type of  Delivery -->
                            <div class="flex flex-row justify-between my-2">
                                <div class="text-black font-normal">{{__('Order Type')}}</div>
                                <div id="orderType" class="text-black font-bold "></div>
                            </div>   
                            <hr class="text-gray-400 my-2">
                            <!-- orders -->
                            <div id="orders"></div>       
                            <!-- Subtotal -->
                            <div class="flex flex-row justify-between my-2">
                                <div class="text-black font-normal">{{__('Subtotal')}}</div>
                                <div id="subtotal" class="text-black font-bold "></div>
                            </div>
                            {{-- delivery fee --}}
                            <div  class="flex flex-row justify-between my-2">
                                <div class="text-black font-normal">{{__('Delivery fee')}}</div>
                                <div id="delivery_fee" class="text-black font-bold "></div>
                            </div>
                            {{-- tax --}}
                            <div class="flex flex-row justify-between my-2">
                                <div class="text-black font-normal">{{__('Tax')}}</div>
                                <div id="tax" class="text-black font-bold "></div>
                            </div>
                            <!-- total -->
                            <div class="flex flex-row justify-between my-2 text-red-600 text-xl font-semibold">
                                <div class="">{{__('Total')}}</div>
                                <div id="total"></div>
                            </div>
                            <div class="flex flex-row justify-between my-2 text-red-600 text-xl font-semibold">
                                <div id="warning_message"></div>
                            </div>    
                            {{-- coupon --}}
                            <div class="input-group input-group my-2">
                                <input type="text" name="coupon" id="coupon" placeholder="{{__('Add Coupons')}}" class="form-control p-3 fix-rounded-right rounded" >
                                <div class="input-group-prepend">
                                    <span onclick="checkCoupon()" class="cursor-pointer input-group-text py-1 px-2 ml-2 border-none bg-green text-white font-semibold rounded">{{__('Valider')}}</span>
                                </div>
                            </div>   
                            <div class="text-red-500 text-sm" id="coupon_msg"></div>
                            <!-- button -->
                            <div class="my-3">
                                <input type="hidden" name="order" id="order">
                                <input type="hidden" name="payment_method" id="payment_method">
                                <input type="hidden" name="orderType" id="orderTypeInput">
                                <button name="register_sub" type="submit" class="form-control mb-3 p-2 border-none bg-green text-white font-semibold rounded">{{__('Make your Order')}}</button>
                                <p class="text-center text-gray-600 text-sm font-light">{{__('Or Call Us at')}}<b class="font-semibold" > {{$market->mobile}}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>        
        </main>
        @include('balde_components.footer')
    @endsection

    @section('extraJs')
        <script type="text/javascript">
                //variabels
                var order = JSON.parse(localStorage.getItem('order'));
                if(order.market.id !==  @json($market).id ) location.href=location.origin+"/notFound"        
                var default_currency = null;
                var currency_right = null;  
                var withcoupon = false;
                // custom check for order type
                if(order.orderType === "TakeAway"){
                    document.getElementById("cash_li").innerHTML = "";
                    // document.getElementById("card_li").innerHTML = "";
                    document.getElementById("pills-home-tab").classList.add("active");
                }
                //functions
                const setDefaultCurrency=currency=>{
                    default_currency=currency
                }
                const setCurrencyRight=isRight=>{
                    currency_right=isRight
                }
                const getCurrency=()=>{
                    fetch('/api/default/currency')
                    .then(response => response.json())
                    .then(response => setDefaultCurrency(response))
                    .catch(err => console.error(err))
                }
                const getCurrencyRight=()=>{
                    fetch('/api/default/currencyRight')
                    .then(response => response.json())
                    .then(response => setCurrencyRight(response != false))
                    .catch(err => console.error(err))
                }
                const wait=()=>{
                    setTimeout(function(){(default_currency != null || currency_right != null) ?showData() : wait();},5)
                }
                const showPrice=(price = 0)=>{
                    if(currency_right != false){
                        return price.toFixed(2) +" "+default_currency
                    }
                    return default_currency+" "+price.toFixed(2) 
                }
                const setMethod=methode=>{
                    document.getElementById("payment_method").setAttribute("value",methode);
                }
                const checkCoupon=()=>{
                    const input = document.getElementById("coupon")
                    const msg = document.getElementById("coupon_msg")
                    coupon= input.value 
                    if (coupon === "") {
                        input.classList.add("is-invalid");
                        msg.innerHTML=`{{__('coupon_invalid')}} `
                    }else{
                        if (!withcoupon) {
                            axios
                            .get(`/api/coupon/check?coupon=${coupon}`)
                            .then(response => response.data )
                            .then(response => {                
                                if(!response.valid){
                                    input.classList.add("is-invalid")
                                    input.value=""
                                    msg.innerHTML=`{{__('coupon_invalid')}} `
                                }else{
                                    input.classList.remove("is-invalid")
                                    input.classList.add("is-valid")
                                    msg.innerHTML=""
                                    applyCoupon(response)
                                    withcoupon=true;
                                }
                            })
                            .catch((err) => {
                                input.classList.add("is-invalid");
                                console.error(err);
                            })
                        } 
                    }  
                }
                const applyCoupon = response =>{
                    products=[];
                    order.orders.forEach(product=>{
                        products.push({
                            "id":product.product_id,
                            "price":product.oldPrice,
                            "numberOfMeals":product.numberOfMeals,
                            "category":product.productCategory
                        })
                    })   
                    var tax = order.market.admin_commission;   
                    var total = order.total ;      
                    products.forEach(product => {
                        var dis = 0 
                        if(response.discountables.products.indexOf(product.id)>=0){
                            if(response.discount_type==="percent"){
                                dis=dis+ product.price-(product.price-(product.price*response.discount)/100)
                            }else{
                                dis=dis+ response.discount
                            }
                        }else if(response.discountables.markets.indexOf(order.market.id)>=0){
                            if(response.discount_type==="percent"){
                                dis=dis+ product.price-(product.price-(product.price*response.discount)/100)
                            }else{
                                dis=dis+ response.discount
                            }
                            
                        }else if(response.discountables.categorys.indexOf(product.category)>=0 ){
                            if(response.discount_type==="percent"){
                                dis=dis+ product.price-(product.price-(product.price*response.discount)/100)
                            }else{
                                dis=dis+ response.discount
                            }
                        }
                        total=total- dis * parseInt(product.numberOfMeals);
                    })

                    // remove the del mark on price if the total price 
                    old_price = document.getElementById("total").textContent
                    new_price = showPrice(total+(total*tax)/100)
                    // alert(old_price + 'ooooooooooooooooooo' + new_price)
                    // alert(total)
                    if(old_price != new_price){
                         const old=`<p class="line-through ...">${document.getElementById("total").textContent}</p>`;
                        document.getElementById("total").innerHTML=`${old}  ${showPrice(total+(total*tax)/100)}`
                    }else{
                        const old=`<h6 class="alert alert-danger ">This Coupon not Valid on this Product</h6>`;
                        document.getElementById("total").innerHTML=`${old_price}` 
                        document.getElementById("warning_message").innerHTML=`${old}`
                    }
                    }
                const setNewAddress=()=>{
                    document.getElementById("address").innerHTML=
                    `
                    <div class="col-span-6 md:col-span-4">
                        <label for="city" class="block text-sm font-medium text-gray-700">
                            {{__('Delivery Addres')}} 
                        </label>
                        <input 
                            type="text" 
                            name="address" 
                            id="address"  
                            placeholder="...." 
                            autocomplete="address"
                            required
                            value="{{ old('address') }}" 
                            class="mt-1 p-2 outline-none  block w-full shadow-sm sm:text-sm rounded  @error('address') form-control is-invalid  @enderror ">
                        @error('address')
                            <div class="text-red-500 text-sm ">{{ $message }}</div>
                        @enderror
                    </div>   
                    <div class="col-span-6 md:col-span-2">
                        <label for="street_address" class="block text-sm font-medium text-gray-700">
                            {{__('Delivery Addres description')}}  
                        </label>
                        <input 
                            type="text" 
                            name="delivery_address_description" 
                            id="delivery_address_description" 
                            autocomplete="delivery_address_description"  
                            placeholder="example : Home"
                            required 
                            value="{{ old('street_address') }}" 
                            class="mt-1 p-2 outline-none  block w-full shadow-sm sm:text-sm rounded  @error('delivery_address_description') form-control is-invalid  @enderror " >
                        @error('delivery_address_description')
                                <div class="text-red-500 text-sm ">{{ $message }}</div>
                        @enderror
                    </div>
                    <input type="hidden" name="address_type" value="costume">
                    `;
                }
                const _orderValid=()=>{
                    if (!order ||
                        !["TakeAway", "Delivery"].includes(order.orderType) ||            
                        order.orders === null ||
                        order.orders.length === 0 ||
                        order.orderType === null ||
                        order.restaurant === null ||
                        order.delivery_fee === null
                    ){
                        localStorage.removeItem("order");
                        location.href=location.origin+"/notFound";
                    }
                }

                const showData=()=>{
                    _orderValid();
                    if (default_currency === null || currency_right === null) {wait()}
                    else{
                        
                        var tax = order.market.admin_commission; //changed by khaled
                        // alert(tax);  
                        var total = order.total ;         
                        if(order.orderType !== "TakeAway"){
                            document.getElementById("delivery_fee").textContent=showPrice(order.delivery_fee); 
                        }else{
                            document.getElementById("delivery_fee").textContent='free';
                            total=order.total-order.delivery_fee;
                            document.getElementById("address").innerHTML=``;
                        }
                        if(document.getElementById('orders').innerHTML === ""){
                            order.orders.forEach(element=>{
                                document.getElementById('orders').innerHTML +=`
                                    <div class="flex flex-row justify-between my-2.5">
                                        <div class="text-black font-normal">
                                            ${element.numberOfMeals} x ${element.product_name}
                                        </div>
                                        <div class="text-black font-bold ">
                                            ${showPrice(element.price)}
                                        </div>
                                    </div>`
                                ;
                            });
                        } 
                        document.getElementById("tax").textContent=tax+" %";  
                        document.getElementById("total").textContent=showPrice(total+(total*tax)/100);
                        document.getElementById("orderType").textContent=order.orderType;
                        document.getElementById("subtotal").textContent=showPrice(order.total-order.delivery_fee);  
                        document.getElementById("order").setAttribute("value",JSON.stringify(order));//undefined
                    
                        if(setMethod.value === undefined && order.orderType == "TakeAway"){    
                            // alert('hi');
                            document.getElementById("payment_method").setAttribute("value",'card');
                        }else if(setMethod.value === undefined  ){
                            document.getElementById("payment_method").setAttribute("value",'cash');
                        }
                        else{
                            document.getElementById("payment_method").setAttribute("value",setMethod.value);
                        }
                        document.getElementById("orderTypeInput").setAttribute("value",order.orderType);
                    }
                }
                
                getCurrency();
                getCurrencyRight();
                wait();
                
        </script>
        <script>
            $(document).ready(function () { $('.form_sub').submit(function (validator) { $('.register_sub').attr('disabled', 'disabled');    });
});

        </script>
    @endsection