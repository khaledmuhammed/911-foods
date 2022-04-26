<template>
    <div id="slides">
        <vueper-slides
            fixed-height="470px"
            class="no-shadow"
            ref="myVueperSlides"
            fade
            autoplay
            slide-image-inside
            progress
            :bullets="false"
            :touchable="false"
        >
            <vueper-slide
                v-for="(slide, index) in slides"
                :key="index"
                :content="slide.content"
                :image="slide.image"
            />
        </vueper-slides>
        <form   id="banner_search_input"
                class=" bg-white outline-none border-none h-14 p-2 rounded flex flex-row justify-between"
                action="/search"
                method="get">
            <input
                class="px-2 bg-white rounded flex-1 outline-none border-none "
                name="search"
                type="search"
                id="search"
                :placeholder="$t('Search for Markets or Products')"
                required
                autocomplete="off"
            >
            <button class="text-green bg-white mr-2" type="submit"> <i class="fas fa-search"></i></button>
        </form>
    </div>
</template>
<script>
export default {
    data() {
        return {
            slides: [],
        };
    },
    created() {
         axios
            .get(`/api/slides`)
            .then((response) => response.data)
            .then(async response => {
               await response.forEach((slide) => {
                    this.slides.push({
                        id: slide.id,
                        image: slide.image,
                        content: `
                            <div id="slider-content" class=" container px-2 flex flex-col items-center" >
                                <h2 class="text-lg md:text-xl lg:text-5xl font-bold text-black py-3">
                                    ${slide.text}
                                </h2>
                            </div>
                    `,
                    });
                });
            })
            .catch(err => console.log(err));


    },
};
</script>
