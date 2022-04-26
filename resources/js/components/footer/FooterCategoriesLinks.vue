<template>
    <nav class="mb-10 list-none">        
        <li v-for="(category, index) in categorys" :key="index" >
            <a :href="`/category/${category.id}`" class="text-sm text-gray-400 hover:text-gray-500 cursor-pointer ">
                {{category.name}}
            </a>
        </li>
    </nav>
</template>

<script>
    export default {
        data() {
            return {
                categorys:[]
            }
        },
        created() {
            axios
            .get(`/api/category/random`)
            .then(response => response.data )
            .then(async response => { 
                this.categorys=response.categorys;
            })
            .catch((err) => {console.log(err);})
        }
    }
</script>
