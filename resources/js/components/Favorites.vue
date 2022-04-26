<template>
    <div class="w-full my-3 mx-0 grid gap-5 grid-cols-1 md:grid-cols-2 lg:grid-cols-2 -mt-1 ">  
        <div v-for="(product,index) in products" :key="index" class="flex flex-col">                       
            <div @click="goToProduct(product.market.id)" class="cursor-pointer box lg:h-44 md:h-56 w-full flex" >
                <div class="h-full w-1/3">
                    <img :src='product.cover !=""? product.cover :"/images/food-placeholder.jpeg"' alt="image" class="h-full w-full rounded-l-md object-cover">
                </div>
                <div class=" flex-1 relative flex flex-col justify-between bg-gray-50 rounded-r-md px-4 pt-4 pb-3">
                    <div class="flex w-full justify-between align-items-start">
                        <div>
                            <span class="text-gray-800 ">
                                {{product.category.name}}
                            </span>
                            <h2 class="text-black text-2xl font-bold ">
                                {{product.product.name}}
                            </h2>
                            <p class="text-gray-400 text-sm">{{product.market.name}}</p>
                            <p class="text-gray-400 text-xs">{{product.market.address}}</p>
                        </div>
                        <div v-if="product.rate !=null" class="bg-gray-200 py-1 px-2 rounded">
                            <span class="text-gray-700 font-semibold">
                                {{product.rate}}    
                            </span>
                            <i class="text-gold fas fa-star"></i>
                        </div>
                    </div>
                    <div class="flex w-full justify-between align-items-baseline">
                        <div class="flex flex-col sm:flex-row items-center">
                            <p class="text-green text-base font-bold sm py-1 px-2">
                                {{product.price}}$
                            </p>
                            <span v-if="product.discount" class="bg-red-600 text-white text-center rounded w-12 text-sm py-1 px-1 ">
                                -{{product.discount}}% 
                            </span>                                    
                        </div>                                
                        <div class="text-sm">
                            <span class="px-1"
                                :class="{ ['line-through text-gray-400']: !product.market.available_for_delivery,['text-gray-600']:product.market.available_for_delivery}">
                                {{$t("Delivery")}} 
                                <i class="fas fa-motorcycle"></i>
                            </span>
                            <span class="px-1"
                                :class="{ ['line-through text-gray-400']: product.market.closed,['text-gray-600']:!product.market.closed}">
                                {{$t("Take away")}}
                                <i class="fas fa-shopping-basket"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-1 py-2.5 flex flex-row justify-between text-sm w-full">
                <span @click="remove(product.product)" class="flex-1 text-red-500 cursor-pointer hover:text-black transition ease-in-out duration-300"> 
                    <i class="fas fa-trash  mr-1  "></i>
                    {{$t("Remove from favorites")}}
                </span>                            
            </div>
        </div>
        <div v-if="canLoadMore && (products.length >= 8) " class="lg:col-span-2 mb-4 md:col-span-2 col-span-1 flex flex-row justify-center">
            <button @click="loadMore()" class="mb-2 py-2 px-6 border-none bg-green custom-btn-blue text-white font-semibold rounded">
                {{$t("Load more")}}  
            </button>
        </div>
        <div v-if="products.length === 0" class="lg:col-span-2 mb-4 md:col-span-2 col-span-1 text-center font-semibold text-2xl text-black">
            {{$t("Empty favorites list")}}
        </div>
    </div>                
</template>

<script>
export default {
    data() {
        return {
            authId:null,
            skip:0,
            products:[],
            canLoadMore:true,
        }
    },
    created() {
        this.authId=this.$attrs.idauth;
        this.getFavoriteFooods();
    },
    methods: {
        getFavoriteFooods(){
            axios
            .get(`/favorites/product/${this.skip}`)
            .then(response => response.data )
            .then(response => {            
                this.products=response.products;
                
            })
            .catch((err) => {this.errorNotify(err);})
        },
        loadMore(){
            this.skip=this.skip+1;
            axios
            .get(`/favorites/product/${this.skip}`)
            .then(response => response.data )
            .then(response => {   
                response.products.forEach(product => {
                  this.products.push(product)  
                });
                if(response.products.length<1){
                    this.canLoadMore=false;
                    this.notify("no more foods to load");
                }
                
            })
            .catch((err) => {this.errorNotify(err);})
        },
        goToProduct(market_id){
            location.href=window.location.origin+'/markets/'+market_id+'#products';
        },
        notify(msg){
            this.$notify({
                group: 'bar',
                title: 'Important message',
                text: msg
            });
        },
        errorNotify(msg){
            this.$notify({
                group: 'bar',
                title: 'Important message',
                type: 'error',
                text: msg
            });
        },
        remove(product){
            if(this.authId === null){
                this.errorNotify('you need to make a sing in to your account ')
            }else{   
                if(confirm("are tou sure that you want to delete this food from your favorites liste ?")){
                   console.log(`/api/favorites/${product.id}/${this.authId}`);
                   axios
                    .post(`/api/favorites/${product.id}/${this.authId}`)
                    .then(response => response.data )
                    .then(response => {   
                        if(response.status ==="warn"){
                            this.errorNotify(response.message);
                            var array=this.products;
                            this.products=[];
                            array.forEach(element => { 
                                if(product.id !== element.product.id){
                                    this.products.push(element);
                                }
                            });
                        }
                        if(response.status==="error"){
                            this.errorNotify(response.error);
                        }
                    })
                    .catch((err) => {this.errorNotify(err);})
                }
                
            }
        }
    }
}
</script>