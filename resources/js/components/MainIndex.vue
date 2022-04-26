<template>
    <div>
        <section class="container py-5">
            <!-- Popular Markets -->
            <div class="flex flex-col py-4">
                <span class="w-40 h-1 bg-green"></span>
                <div class="flex items-center justify-between">
                    <h2 class="text-black font-bold text-4xl pt-4 pb-2">
                        {{ $t("Popular Markets") }}
                    </h2>
                    <a href="/markets" class="btn bg-green custom-btn-blue  rounded-3xl py-2 px-4 leading-6">
                        {{ $t("View all") }}
                    </a>
                </div>
                <p class="text-gray-400">{{ $t("Popular Markets Description") }}</p>
                <div class="grid gap-5 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 py-4">
                    <div v-for="(market, index) in markets" :key="index" class="box h-72 mt-1 w-full">
                        <div @click="goToMarket(market.market.id)" class="h-2/3 w-full relative cursor-pointer">
                            <span v-if="market.field" class="absolute text-white rounded top-3 left-3 text-sm font-semibold py-1 px-2" style="background-color: #333">
                                {{ market.field }}
                            </span>
                            <span v-if="market.market.closed" class="absolute text-white rounded top-3 right-3 text-sm font-semibold py-1 px-2 bg-red-600">
                                {{ $t("Closed") }}
                            </span>
                            <img :src="market.cover" alt="restaurant" class="h-full w-full rounded"/>
                            <div class="absolute w-full overflow-hidden px-3 py-2.5 bottom-0 flex flex-col justify-center rounded-b-sm" style="background: rgba(92, 92, 92, 0.315)">
                                <h2 class="text-white text-xl font-semibold truncate">
                                    {{ market.market.name }}
                                </h2>
                                <p class="text-gray-100 text-xs truncate">
                                    {{ market.market.address }}
                                </p>
                            </div>
                        </div>
                        <div class="px-1 py-3 flex flex-row justify-between text-sm w-full">
                            <p v-html="market.market.description" class="text-gray-400 flex-1 h-24 overflow-hidden"/>
                            <div class="w-5/12 flex flex-row justify-end">
                            <div class="flex flex-col">
                                <div class="text-gold flex-row flex justify-end items-center">
                                    <div v-if="market.rate" class="bg-gray-200 py-1 px-2 rounded">
                                        <span class="text-gray-700 font-semibold">
                                            {{ market.rate }}
                                        </span>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                                <div class="flex-row flex justify-end items-center mt-1"
                                    :class="{
                                        ['line-through text-gray-400']: !market.market.available_for_delivery,
                                        ['text-gray-600']:market.market.available_for_delivery,
                                    }">
                                    {{ $t("Delivery") }}
                                    <i class="fas fa-motorcycle ml-1"></i>
                                </div>
                                <div class="flex-row flex justify-end items-center mt-1"
                                    :class="{
                                        ['line-through text-gray-400']:
                                        market.market.closed,
                                        ['text-gray-600']: !market.market.closed,
                                    }">
                                    {{ $t("Take away") }}
                                    <i class="fas fa-shopping-basket ml-1"></i>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="my-5 w-full h-60 md:h-96 relative rounded">
                    <img src="/images/bg2.jpg" alt="img" class="absolute top-0 left-0 w-full h-full object-cover rounded"/>
                    <div style="background: rgba(0, 0, 0, 0.593)" class="rounded w-full h-full absolute p-8 md:p-14 flex flex-col justify-center align-items-start">
                        <b class="text-white text-lg my-2">{{ app_name }}</b>
                        <h2 class="text-white text-xl md:text-5xl font-bold my-2">{{$t("More than", { int: $attrs.countmarket - 1 })}}</h2>
                        <p class="text-gray-200 text-sm mb-2 md:mb-4 w-1/2">{{ $t("index More than description") }}</p>
                        <br class="hidden md:inline" />
                        <a href="/markets" class="btn bg-green custom-btn-blue  rounded-pill py-2 px-4 leading-8">{{ $t("View all") }}</a>
                    </div>
                </div>
            </div>
            <!-- Top rated Products -->
            <div class="flex flex-col py-2">
                <span class="w-40 h-1 bg-green"></span>
                <div class="flex align-items-center justify-between">
                    <h2 class="text-black font-bold text-4xl pt-4 pb-2">
                        {{ $t("Top Rated Products") }}
                    </h2>
                    <a  href="/products" class="btn bg-green custom-btn-blue rounded-3xl py-2 px-4 leading-6">
                        {{ $t("View all") }}
                    </a>
                </div>
                <p class="text-gray-400">
                    {{ $t("Top Rated Products Description") }}
                </p>
                <div class="grid gap-5 grid-cols-1 md:grid-cols-2 py-4">
                    <div v-for="(product, index) in products" :key="index" class="cursor-pointer box h-44 w-full flex"
                        @click="goToProduct(product.market.id)">
                        <div class="h-full w-1/3">
                            <img :src="product.cover" alt="image" class="h-full w-full rounded-l-md object-cover"/>
                        </div>
                        <div class="flex-1 relative flex flex-col justify-between bg-gray-50 rounded-r-md px-4 pt-4 pb-3">
                            <div class="flex w-full justify-between align-items-start">
                                <div>
                                    <span class="text-gray-800">{{ product.category.name }}</span>
                                    <h2 class="text-black text-2xl font-bold">{{ product.product.name }}</h2>
                                    <p class="text-gray-400 text-sm">{{ product.market.name }}</p>
                                    <p class="text-gray-400 text-xs">{{ product.market.address }}</p>
                                </div>
                                <div v-if="product.rate" class="bg-gray-200 py-1 px-2 rounded">
                                    <span class="text-gray-700 font-semibold">{{ product.rate }}</span>
                                    <i class="text-gold fas fa-star"></i>
                                </div>
                            </div>
                            <div class="flex w-full justify-between align-items-baseline">
                                <div class="flex flex-row items-center">
                                    <div v-html="product.price" class="text-green text-base font-bold sm py-1 px-2"/>
                                    <span v-if="product.discount" class="bg-red-600 text-white rounded text-sm py-1 px-2">
                                        -{{ product.discount }} %
                                    </span>
                                </div>
                                <div class="text-sm">
                                    <span class="px-1" :class="{
                                            ['line-through text-gray-400']: !product.market.available_for_delivery,
                                            ['text-gray-600']: product.market.available_for_delivery,
                                            ['line-through text-gray-400']: product.market.closed,
                                            ['text-gray-600']: !product.market.closed,
                                        }">
                                            {{ $t("Delivery") }}
                                            <i class="fas fa-motorcycle"></i>
                                    </span>
                                    <span class="px-1"
                                        :class="{
                                            ['line-through text-gray-400']: product.market.closed,
                                            ['text-gray-600']: !product.market.closed,
                                        }">
                                            {{ $t("Take away") }}
                                            <i class="fas fa-shopping-basket"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    export default {
    props: ["countMarket","app_name"],
    data() {
        return {
        markets: [],
        products: [],
        };
    },
    created() {
        this.topRatedMarkets();
        this.topRatedProducts();
    },
    methods: {
        topRatedMarkets(){
            axios
            .get(`/api/markets/topRated`)
            .then((response) => response.data)
            .then((response) => {
                response.markets.forEach((element) => {
                    this.markets.push(element);
                });
            })
            .catch((err) => {
                console.log(err);
            });
        },
        topRatedProducts(){
            axios
            .get(`/api/products/topRated`)
            .then((response) => response.data)
            .then((response) => {
                response.products.forEach((element) => {
                    this.products.push(element);
                });
            })
            .catch((err) => {
                console.log(err);
            });
        },
        goToProduct(market_id){
            location.href=window.location.origin+'/markets/'+market_id+'#products';
        },
        goToMarket(market_id){
            location.href=window.location.origin+'/markets/'+market_id;
        }
    },
    };
</script>