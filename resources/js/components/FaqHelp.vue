<template>
     <div>
        <notifications position="bottom right" width="400px" group="foo" />
        <!-- topic -->
        <section class="py-16 flex flex-col bg-gray-100">
			<div class="container">
                <div class="w-full  ">
                    <div class="w-full  self-center content-center flex flex-row justify-center -mt-4 mb-2">
                        <p class="border-1 border-green w-44"></p>
                    </div>
                    <h2 class="text-black text-4xl font-bold py-2 text-center" >
                        {{$t("Select a topic")}}
                    </h2>
                    <p class="text-gray-600 text-md font-normal  py-2 text-center">
                        {{$t("Select a topic description")}}
                    </p>
                    <div  v-if="categorys.length < 1" class="text-2xl text-black font-semibold text-center flex flex-col justify-center items-center ">
                        <p class="my-2">
                            {{$t("No Topic is Found")}}
                        </p>
                    </div>
                    <div class="w-full my-3 grid gap-7 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 -mt-1 ">
                        <div v-for="(category, index) in categorys" :key="index" @click="about(category)" class="box_help px-2 rounded bg-white shadow-md h-56  w-full cursor-pointer flex flex-col justify-center items-center ">
                            <i class="far fa-comments fa-4x fa-fw text-green"></i>
                            <h3 class="text-black text-xl font-bold">
                                {{category.name}}
                            </h3>
                            <p class="text-center text-gray-500">
                                {{$t("Topic box description" , { category_name: category.name } )}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
		</section>
        <!-- Popular articles -->
        <section id="help-articles" class="flex flex-col pt-2 pb-5 container my-3 bg-white">
            <span class="w-40 my-2 border-1 border-green"></span>
            <div class="flex items-center justify-between">
                <h2 class="text-black text-4xl font-medium pt-2 pb-3">
                    {{$t("Popular articles")}}
                </h2>
                <button @click="viweAll()" v-if="faqs.length != faqsSelected.length" class="btn bg-green custom-btn-blue  rounded-3xl py-2 px-4 leading-6">
                    {{$t("View all")}}
                </button>
            </div>
            <div class="flex mb-3 justify-between">
                <p class="text-lg text-gray-700">
                    {{$t("Popular articles Description")}}
                </p>
            </div>
            <div class="grid gap-1 my-2 grid-cols-1 ">
                <div v-if="faqsSelected.length < 1" class="text-2xl text-black font-semibold text-center flex flex-col justify-center items-center " >
                    <p class="my-2">
                        {{$t("No Articles is Found")}}
                    </p>
                    <p class="my-2" v-if="faqs.length != faqsSelected.length">
                        {{$t("In category", { category_name: categorySelected.name })}}
                    </p>
                </div>
                <div v-for="(faq, index) in faqsSelected" :key="index" onclick="showArticle(this)" class="flex-col flex w-full article   ">
                    <div class="bg-gray-100 mb-0.5 p-4 w-full flex flex-row justify-between items-center cursor-pointer rounded">
                        <h1 v-html="faq.faq.question" class="text-lg text-gray-400 font-semibold"></h1>
                        <i id="icon" class="fa fa-plus text-green" ></i>
                    </div>
                    <div id="desc" class="bg-gray-100 text-gray-600 text-sm p-4 w-full hidden  rounded ">
                        <p v-html="faq.faq.answer"></p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                faqs:[],
                faqsSelected:[],
                categorys:[],
                categorySelected:null,
            }
        },
        created() {
            this.getFaqs();
            this.getCategorys();
        },
        methods: {
            getFaqs(){
                axios
                .get(`/api/faq/`)
                .then(response => response.data )
                .then(async response => {
                this.faqs=response.faqs;
                this.faqsSelected=response.faqs;
                })
                .catch((err) => {this.errorNotify(err);})
            },
            getCategorys(){
                axios
                .get(`/api/faq/categorys`)
                .then(response => response.data )
                .then(response => {
                    this.categorys=response.categories;
                })
                .catch((err) => {this.errorNotify(err);})
            },
            about(category){
                this.faqsSelected=[];
                this.categorySelected=category;
                this.faqs.filter(function(faq) {
                    return faq.categorie.id === category.id
                }).forEach(element => {
                    this.faqsSelected.push(element)
                });
                let scrollTo = document.getElementById('help-articles').offsetTop;
                window.scrollTo(0, scrollTo-60);
            },
            viweAll(){
                this.faqsSelected=this.faqs;
            },
            // notify with error message
            errorNotify(msg){
                this.$notify({
                    group: 'foo',
                    title: 'Important message',
                    type: 'error',
                    text: msg
                });
            },
        },
    }
</script>
