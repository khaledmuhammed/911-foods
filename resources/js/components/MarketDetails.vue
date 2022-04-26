<template>
  <div class="relative w-full container flex flex-col lg:flex-row my-20">  
    <div class="flex-1 lg:mr-10">
      <ul class="nav nav-pills" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link font-semibold py-2.5 px-4 bg-transparent text-gray-600 transition duration-300 ease-in-out active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
            {{ $t("Menu") }}
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a
            class="nav-link font-semibold py-2.5 px-4 bg-transparent text-gray-600 transition duration-300 ease-in-out"
            id="profile-tab"
            data-toggle="tab"
            href="#profile"
            role="tab"
            aria-controls="profile"
            aria-selected="false"
          >
            {{ $t("INFORMATION") }}
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a
            class="nav-link font-semibold py-2.5 px-4 bg-transparent text-gray-600 transition duration-300 ease-in-out"
            id="contact-tab"
            data-toggle="tab"
            href="#contact"
            role="tab"
            aria-controls="contact"
            aria-selected="false"
          >
            {{ $t("REVIEWS") }}
          </a>
        </li>
      </ul>
      <div class="tab-content mb-4" id="pills-tabContent">
        <!-- Menu content -->
        <div class="tab-pane fade show active pt-10 flex flex-col" id="home" role="tabpanel">
          <p v-if="market" v-html="market.description"></p>
          <h1 class="text-center text-black font-bold text-4xl my-10">
            {{ $t("Our Menu") }}
          </h1>

          <div v-for="(Category, index) in categorys" :key="index" class="my-5" id="foods">
            <div v-if="Category">
              <a :href="`/category/${Category[0].category.id}`" class="text-left text-black hover:text-gray-800 no-underline cursor-pointer font-semibold text-xl mb-2">
                {{ Category[0].category.name }}
              </a>
              <div class="my-3">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th scope="col" class="col-8 text-black">
                        {{ $t("Product") }}
                      </th>
                      <th scope="col" class="text-black text-center">
                        {{ $t("Price") }}
                      </th>
                      <th scope="col" class="text-black text-center">
                        {{ $t("Order") }}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(product, index) in Category" :key="index">
                      <td scope="row">
                        <div class="w-full py-2 flex-row flex">
                          <div v-if="authId != null" class="flex flex-col justify-center items-center h-14 mr-2 pr-1">
                            <i @click="addFoodToWishlist(product)" :id="`btn-heart-toProduct-${product.product.id}`"
                                :class="{
                                'fas text-red-500': product.isLikedBy.includes(authId),
                                'far text-green': !product.isLikedBy.includes(authId),
                              }"
                              class="fa-heart text-xl cursor-pointer"/>
                          </div>
                          <img :src="product.cover" class="h-14 w-20 object-cover rounded" alt="image" />
                          <div class="flex flex-col justify-center ml-5">
                            <div class="md:text-base text-sm text-black font-semibold">
                              {{ index + 1 }}. {{ product.product.name }}
                            </div>
                            <div class="text-gray-700 font-medium text-xs md:text-sm">
                              <span v-if="product.product.featured === 1" class="badge bg-green py-1 px-2 text-xs text-white">
                                {{ $t("Featured") }}
                              </span>
                              <span v-if="product.product.deliverable === 1">
                                {{ $t("Delivery") }}
                                <i class="fas fa-motorcycle"></i>
                              </span>
                              <span v-else class="line-through text-gray-400">
                                {{ $t("Delivery") }}
                                <i class="fas fa-motorcycle"></i>
                              </span>
                              <span v-if="product.rate">
                                |
                                {{ product.rate }}
                                <i class="ml-1 fas fa-star text-gold"></i>
                              </span>                              
                            </div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div v-html="product.price_format" class="text-black text-center font-bold pt-4 min-w-max"/>
                      </td>
                      <td>
                        <div class="pt-4 text-center">
                          <span onclick="show_make_orde(this)" class="make_order font-bold text-green fas fa-plus mt-1 cursor-pointer"/>
                          <div class="order hidden text-left absolute w-60 bg-white shadow-sm rounded mt-2 -ml-44 border-gray-300 border-1 p-2" >
                            <!-- Add ingredients -->
                            <div v-for="(options_group, index) in product.options_groups" :key="index">                    
                              <p class="font-semibold text-black text-sm mt-3">{{ index }}</p>
                              <hr class="my-1 text-gray-500" />
                              <label v-for="(item, index) in options_group" :key="index" :for="`${item.id}-${index}`" class="input_checkbox relative block cursor-pointer select-none pl-8 ml-2 mb-2 text-base">
                                <div class="flex flex-row justify-between pr-3">
                                  <p>{{ item.name }}</p>
                                  <p>
                                    <span class="font-semibold text-green">{{
                                      showPrice(item.price)
                                    }}</span>
                                  </p>
                                </div>
                                <input type="checkbox" v-model="options" :value="item" class="absolute opacity-0 cursor-pointer h-0 w-0" :id="`${item.id}-${index}`"/>
                                <span class="input_checkbox_checkmark rounded absolute top-0 left-0 h-5 w-5 bg-gray-300"/>
                              </label>
                            </div>
                            <!-- The number of meals -->
                            <p class="font-semibold text-black text-sm mt-3">{{ $t("Total meals") }}</p>
                            <hr class="my-1 text-gray-500" />
                            <div class="my-3">
                              <input class="form-control mb-2 p-2 cursor-pointer" v-model="numberOfMeals" type="number" value="1" min="1" max="50"/>
                            </div>
                            <button @click="addToOrders(product)" class="bg-green text-white font-semibold w-full py-1.5 rounded">
                              {{ $t("Add to your order") }}
                            </button>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- Information content -->
        <div v-if="market" class="tab-pane fade pt-10 flex flex-col" id="profile" role="tabpanel">
            <div v-html="market.description"/>
            <div v-if="gallerie">
                <div v-if="gallerie.length > 0">
                    <h1 class="text-center text-black font-bold text-2xl mt-10 mb-3">{{ $t("Pictures from our users") }}</h1>
                    <div class="flex flex-row justify-center">
                    <div class="cursor-pointer" @click="showGallerie" v-for="(object, index) in gallerie" :key="index">
                        <img v-if="index < 4" :src="object.image" :alt="object.text" class="w-16 h-16 lg:w-32 lg:h-32 ml-1 lg:mx-1 shadow-sm object-cover"/>
                        <div v-if="gallerie.length >= 4 && index === 4" class="relative w-16 h-16 lg:w-32 lg:h-32 ml-1 lg:mx-1 shadow-sm">
                        <img :src="object.image" :alt="object.text" class="w-full h-full absolute object-cover" style="z-index: -10"/>
                        <div class="w-full h-full flex flex-row justify-center items-center" style="background-color: rgba(0, 0, 0, 0.645)" >
                            <div class="text-white text-3xl font-semibold"> +{{ gallerie.length - 1 }} </div>
                        </div>
                        </div>
                    </div>
                    </div>
                    <div class="hidden flex-col items-center" id="gl">
                        <div class="w-full flex flex-row justify-end">
                            <i @click="closeGallerie" class="mt-6 mr-10 fa fa-times-circle text-white text-3xl cursor-pointer" aria-hidden="true"/>
                        </div>
                        <div class="w-10/12 mt-5 self-center shadow-md">
                            <vueper-slides slide-image-inside fade :touchable="false">
                            <vueper-slide v-for="(slide, i) in gallerie" :key="i" :image="slide.image" fixed-height="800px"/>
                            </vueper-slides>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 rounded my-8 py-2">
            <h1 class="text-center text-black font-bold text-2xl mt-10 mb-3">
              {{ $t("get_to", { market_name: market.name }) }}
            </h1>
            <div class="w-full flex flex-col lg:flex-row lg:justify-between">
              <div class="flex-1 flex flex-col mr-1 justify-start py-2 px-3">
                <h1 class="text-black font-semibold text-lg py-2">
                  {{ $t("Address") }}
                </h1>
                <p class="text-base text-gray-600 font-medium">
                  <i class="fas fa-home"></i>
                  {{ market.address }}
                </p>
                <a
                  @click="getDirections()"
                  class="hover:underline cursor-pointer hover:text-gray-700 text-green font-semibold text-sm"
                >
                  {{ $t("Get directions") }}
                </a>
                <span class="text-transparent bg-transparent h-6"></span>
                <h1 class="text-black font-semibold text-base mb-2">
                  {{ $t("Follow Us") }}
                </h1>
                <div class="flex flex-row">
                  <a
                    href="#"
                    class="py-1.5 px-2 bg-gray-400 text-white rounded cursor-pointer hover:bg-blue-600 mx-1 transition ease-in-out duration-300"
                  >
                    <i class="fab fa-facebook"></i>
                  </a>
                  <a
                    href="#"
                    class="py-1.5 px-2 bg-gray-400 text-white rounded cursor-pointer hover:bg-pink-600 mx-1 transition ease-in-out duration-300"
                  >
                    <i class="fab fa-instagram"></i>
                  </a>
                  <a
                    href="#"
                    class="py-1.5 px-2 bg-gray-400 text-white rounded cursor-pointer hover:bg-blue-400 mx-1 transition ease-in-out duration-300"
                  >
                    <i class="fab fa-twitter"></i>
                  </a>
                </div>
              </div>
              <div class="flex-1 flex flex-col mr-1 justify-start py-2 px-3">
                <h1 class="text-black font-semibold text-lg py-2">
                  {{ $t("Contact Us") }}
                </h1>
                <p class="text-base text-gray-600 font-normal">
                  <i class="fas fa-mobile-alt w-5"></i> {{ market.mobile }}
                  <br />
                  <i class="fas fa-phone w-5"></i> {{ market.phone }}
                </p>
                <h1 class="text-black font-semibold text-lg py-2">
                  {{ $t("INFORMATION") }}
                </h1>
                <!-- <p class="text-base text-gray-800 font-bold"></p> -->
                <p
                  class="text-base text-gray-600 font-normal"
                  v-html="market.information"
                ></p>
                <span class="text-transparent bg-transparent h-6"></span>
                <span
                  v-if="market.closed"
                  class="text-red-500 border-2 border-red-500 w-max rounded px-2 py-1 text-sm"
                >
                  {{ $t("Closed Now") }}
                </span>
                <span
                  v-if="!market.closed"
                  class="text-green border-2 border-green w-max rounded px-2 py-1 text-sm"
                >
                  {{ $t("Open Now") }}
                </span>
              </div>
            </div>
          </div>
                  
        </div>
        <!-- REVIEWS content -->
        <div
          class="tab-pane fade pt-10 flex flex-col"
          id="contact"
          role="tabpanel"
        >
          <!-- ratiing -->
          <div class="flex flex-col lg:flex-row">
            <div
              class="w-full lg:w-52 h-32 bg-gray-800 rounded mb-3 flex flex-row justify-center items-center text-white"
            >
              <div v-if="market" class="flex flex-col text-center">
                <p class="text-5xl font-extrabold">
                  {{ market_rate }}
                </p>
                <span class="font-bold">
                  <span v-if="market_rate <= 5 && market_rate >= 4">
                    {{ $t("reviews >4") }}
                  </span>
                  <span v-if="market_rate < 4 && market_rate >= 3">
                    {{ $t("reviews >3") }}
                  </span>
                  <span v-if="market_rate < 3 && market_rate >= 2">
                    {{ $t("reviews >2") }}
                  </span>
                  <span v-if="market_rate < 2 && market_rate >= 1">
                    {{ $t("reviews >1") }}
                  </span>
                </span>
                <span v-if="reviews" class="text-xs font-medium">
                  {{ $t("Based on reviews", { reviews: reviews.length }) }}
                </span>
              </div>
            </div>
            <div class="flex-1 flex flex-col justify-center mb-3 lg:mx-4"></div>
            <div class="flex-1 flex flex-col justify-center mb-3 lg:mx-4">
              <!-- btn Leave review -->
              <div v-if="market" class="flex flex-row justify-end">
                <a
                  :href="`/markets/review/${market.id}`"
                  class="btn bg-green custom-btn-blue  rounded font-medium "
                >
                  {{ $t("Leave review") }}
                </a>
              </div>
            </div>
          </div>
          <!-- Reviews -->
          <div class="flex flex-col">
            <!-- Review box-->
            <div
              v-for="(review, index) in reviews"
              :key="index"
              class="rounded border-gray-300 border-1 my-2 w-full shadow-sm flex flex-col"
            >
              <!-- Review -->
              <div class="flex flex-row">
                <div class="w-3/12 py-4 flex flex-col items-center">
                  <img
                    :src="review.user_avatar"
                    class="rounded-full object-cover w-14 h-14 mb-2"
                    alt="user_avatar"
                  />
                  <div class="text-black font-semibold text-center">
                    {{ review.user.name }}
                  </div>
                </div>
                <div class="w-9/12 py-4 flex flex-col items-start pr-4">
                  <!-- rating and date -->
                  <div
                    class="flex flex-col md:flex-row mb-2 justify-between w-full md:items-center"
                  >
                    <div class="text-black font-semibold text-sm">
                      <span class="text-green font-bold">
                        <span class="text-3xl">{{ review.rate }}</span
                        >/5
                      </span>
                      {{ $t("Average rating") }}
                    </div>
                    <div class="text-gray-500 italic font-medium text-sm">
                      {{ review.published }}
                    </div>
                  </div>
                  <div
                    class="text-sm my-2 text-gray-500 font-light"
                    v-html="review.review"
                  ></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Order Summary -->
    <div class="w-full rounded lg:w-1/3">
      <!-- order liste & prices -->
      <div class="w-full border-2 rounded border-gray-300 border-dotted">
        <div class="text-center border-b-2 bg-gray-100 border-gray-300 border-dotted py-3">
          <p class="text-2xl font-bold">{{ $t("Order Summary") }}</p>
          <p class="text-red-600 font-semibold">{{ $t("Order Summary description") }}</p>
        </div>
        <div v-if="market" class="py-3 px-4 flex flex-col">
          <div v-for="(order, index) in orders" :key="index" class="flex flex-row justify-between my-2">
            <div @click="removeFromOrders(index)" class="text-black font-normal cursor-pointer hover:text-red-600">
              <i class="fas fa-trash-alt mr-1"/> {{ order.numberOfMeals }} x {{ order.product_name }}
              <span v-if="order.options.length > 0" class="text-xs text-gray-400">({{ $t("With Options") }})</span>
            </div>
            <div class="text-black font-bold">{{ showPrice(order.price) }}</div>
          </div>
          <br/>
          <div class="flex flex-row justify-between my-2">
            <div class="text-black font-normal">{{ $t("Subtotal") }}</div>
            <div class="text-black font-bold">{{ showPrice(ordersTotalPrice) }}</div>
          </div>
          <div v-if="orderType === 'Delivery'" class="flex flex-row justify-between my-2">
            <div class="text-black font-normal">{{ $t("Delivery fee") }}</div>
            <div class="text-black font-bold">{{ showPrice(market.delivery_fee) }}</div>
          </div>
          <div class="flex flex-row justify-between my-2 text-red-600 text-xl font-semibold">
            <div class="">{{ $t("Total") }}</div>
            <div v-if="orderType === 'Delivery'">{{ showPrice(ordersTotalPrice + market.delivery_fee) }}</div>
            <div v-else>{{ showPrice(ordersTotalPrice) }}</div>
          </div>
          <hr class="text-gray-400 my-2" />
          <div class="flex flex-row justify-between mx-1 my-2 text-gray-800 font-normal">
            <label class="flex-1 input_radio block relative pl-10 cursor-pointer text-lg select-none">
              {{ $t("Delivery") }} <input type="radio" v-model="orderType" value="Delivery" checked="checked" name="radio" class="absolute cursor-pointer opacity-0"/>
              <span class="checkmark absolute top-1 left-0 w-5 h-5 rounded-full bg-gray-300"/>
            </label>
            <label class="flex-1 input_radio block relative pl-10 cursor-pointer text-lg select-none">
              {{ $t("Take away") }}<input type="radio" v-model="orderType" value="TakeAway" name="radio" class="absolute cursor-pointer opacity-0"/>
              <span class="checkmark absolute top-1 left-1 w-5 h-5 rounded-full bg-gray-300"/>
            </label>
          </div>
          <hr class="text-gray-400 my-2" />

          <!-- button -->
          <div class="my-2">
            <button @click="makeOrder" class="form-control mb-2 p-2 border-none bg-green text-white font-semibold">
              {{ $t("Make your Order") }}
            </button>
            <p class="text-center text-gray-600 text-sm font-light">
              {{ $t("Make your Order description") }}
            </p>
          </div>
        </div>
      </div>
      <!-- share -->
      <div class="my-3 flex flex-row justify-around">
        <button class="border font-semibold px-3 py-1.5 rounded text-blue-600 hover:text-white hover:bg-blue-600 transition ease-in-out duration-300">
            <i class="fab fa-facebook-f"></i> {{ $t("Share") }}
        </button>
        <button class="border font-semibold px-3 py-1.5 rounded text-pink-600 hover:text-white hover:bg-pink-600 transition ease-in-out duration-300">
            <i class="fab fa-instagram"></i> {{ $t("Share") }}
        </button>
        <button class="border font-semibold px-3 py-1.5 rounded text-blue-400 hover:text-white hover:bg-blue-400 transition ease-in-out duration-300">
            <i class="fab fa-twitter"></i> {{ $t("Share") }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
        return {
            authId: null,
            currency_right: null,
            default_currency: null,
            market_id  : null,
            numberOfMeals: 1,
            market: null,
            gallerie: null,
            reviews: null,
            products: null,
            pictures: null,
            market_rate: null,
            categorys: null,
            orders: [],
            ordersTotalPrice: 0,
            orderType: "Delivery",
            orderDate: null,
            orderTime: null,
            options: [],
        }
    },
    created() {
        this.currency_right = this.$attrs.currency_right
        this.default_currency = this.$attrs.default_currency
        this.market_id = this.$attrs.market_id
        if(this.$attrs.idauth){
            this.authId = this.$attrs.idauth
        }
        this.getDetail();
        this.getGallerie();
    },
    methods: {
        showGallerie() {
            console.log("ok");
            window.document.getElementById("gl").classList.remove("hidden");
            window.document.getElementById("gl").classList.add("flex");
        },
        closeGallerie() {
            window.document.getElementById("gl").classList.add("hidden");
            window.document.getElementById("gl").classList.remove("flex");
        },
        showPrice(price = 0) {
            if (this.$attrs.currency_right != false) {
                return price.toFixed(2) + " " + this.$attrs.default_currency;
            }
            return this.$attrs.default_currency + " " + price.toFixed(2);
        },
        getDetail(){
            axios
            .get(`/api/markets/detail/${this.market_id}`)
            .then(response => response.data)
            .then(response => {
                this.market = response.market;
                this.reviews = response.reviews;
                this.products = response.products;
                this.pictures = response.pictures;
                this.market_rate = response.market_rate;
                let categorys = [];
                response.products.forEach((product) => (categorys[product.category.id] = []));
                response.products.forEach((product) => {
                    if (product.product.featured === 1) {
                    categorys[product.category.id].unshift(product);
                    } else {
                    categorys[product.category.id].push(product);
                    }
                });
                
                this.categorys = categorys.filter(cat => cat !== null);
            })
            .catch(err =>console.error(err))
        },
        getGallerie(){
          axios
          .get(`/api/galleries/market/${this.market_id}`)
          .then(response => response.data)
          .then(response => this.gallerie = response)
          .catch((err) => console.error(err))
        },
        addFoodToWishlist(product) {
            if (this.authId === null) {
              this._notify("error",this.$t("you need to make a sing in to your account"));
            } else {
              axios
              .post(`/api/favorites/${product.product.id}/${this.authId}`)
              .then(response => response.data)
              .then(response => {
                  if (response.status === "success") {
                      this.heartChange(product.product.id)
                      this._notify("success",response.message)
                  }
                  if (response.status === "warn") {
                      this.heartChange(product.product.id)
                      this._notify("warn",response.message)

                  }
                  if (response.status === "error") {
                      this._notify("error",response.error);
                  }
              })
              .catch((err) => this._notify("error",err));
            }
        },
        heartChange(product_id) {
            var btnHeart = window.document.getElementById(`btn-heart-toProduct-` + product_id);
            btnHeart.classList.toggle("far");
            btnHeart.classList.toggle("fas");
            btnHeart.classList.toggle("text-green");
            btnHeart.classList.toggle("text-red-500");
        },
        _notify(type,msg){
            this.$notify({
                group: 'bar',
                title: this.$t("Important message"),
                type: type,
                text: msg
            });
        },    
        getDirections() {
            window.open(`https://www.google.com/maps/dir//${this.market.latitude},${this.market.longitude}/@$${this.market.latitude},${this.market.longitude},15z`);
        },
        addToOrders(product) {
          let orderPrice = product.price;
          let optionsPrice = 0;
          this.options.forEach((element) => {
            optionsPrice = optionsPrice + element.price;
          });
          this.options = this.options.map((option) => option.id);
          orderPrice = orderPrice + optionsPrice;
          this.orders.push({
            product_id: product.product.id,
            product_name: product.product.name,
            options: this.options,
            numberOfMeals: this.numberOfMeals,
            oldPrice: product.price ,
            productCategory: product.category.id,
            productMarket: product.product.market_id,
            price: orderPrice * this.numberOfMeals,
          });
          this.ordersTotalPrice = this.ordersTotalPrice + orderPrice * this.numberOfMeals;
          this.options = [];
          this.numberOfMeals = 1;
          Array.from( window.document.querySelectorAll("table > tbody > tr > td > div >div.order"))
          .forEach((element) => {
              element.classList.add("hidden");
              let btn = element.previousElementSibling;
              btn.classList.add("fa-plus");
              btn.classList.remove("fa-times");
              btn.classList.add("text-green");
              btn.classList.remove("text-red-500");
          }); 
          this._notify("success",`${product.product.name +" "+ this.$t("Added to your Cart")}`) 
        },
        removeFromOrders(index) {
          let order = this.orders[index];
          if (this.ordersTotalPrice > 0) this.ordersTotalPrice = this.ordersTotalPrice - order.price;
          if (index > -1) this.orders.splice(index, 1);
          this._notify("error",`${order.product_name +" "+  this.$t("Removed from your Cart")}`)
        },
        makeOrder() {
          if (this.orders.length === 0) {
            this._notify("error", this.$t("you need to add items to your order"))
            return;
          }
          if ( this.market_id === null || this.orderType === null || this.ordersTotalPrice === null) {
            this._notify("error",this.$t("something wrong"));
            return;
          }
          const order = {
            market: this.market,
            orders: this.orders,
            orderType: this.orderType,
            total: this.ordersTotalPrice + this.market.delivery_fee,
            delivery_fee: this.market.delivery_fee
          }
          localStorage.removeItem("order");
          localStorage.setItem("order",JSON.stringify(order));
          location.href = location.origin+`/order/market/${this.market.id}`;
        },
    },
  };
</script>
