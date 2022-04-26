$('.slider').slick({
    infinite: true,
    slidesToShow: 5,
    slidesToScroll: 1,
    dots: false,
    speed: 500,
    prevArrow:"<div class='absolute h-full transform rotate-180  cursor-pointer -top-4  -left-4 flex items-center'><img src='/images/next.png' class='w-6'></div>",
    nextArrow:"<div class='absolute h-full -top-4 -right-4 cursor-pointer flex items-center'><img src='/images/next.png' class='w-6'></div>",
    responsive: [
        {
            breakpoint: 1280,
            settings: {
                slidesToShow: 4,
            }
        },
        {
            breakpoint: 980,
            settings: {
                slidesToShow: 3,
            }
        },
        {
            breakpoint: 768,
            settings: {
                slidesToShow: 2,
            }
        },
        {
            breakpoint: 640,
            settings: {
                slidesToShow: 1,
            }
        }
    ]
});