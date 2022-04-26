<template>
    <nav class="mb-10 list-none">        
        <li v-for="(field, index) in fields" :key="index" >
            <a :href="`/fields/${field.id}`" class="text-sm text-gray-400 hover:text-gray-500 cursor-pointer ">
                {{field.name}}
            </a>
        </li>
    </nav>
</template>

<script>
    export default {
        data() {
            return {
                fields:[]
            }
        },
        created() {
            axios
            .get(`/api/fields/random`)
            .then(response => response.data )
            .then(async response => { 
                this.fields=response.fields;
            })
            .catch((err) => {console.log(err);})
        }
    }
</script>
