$('.slick-greetings').slick({
    dots: false,
    infinite: true,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: true,
    responsive: [
        {
            breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    dots: true,
                    autoplay: true,
                    autoplaySpeed: 3000,
                }
            },
        {
            breakpoint: 992,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    // dots: true,
                    autoplay: true,
                    autoplaySpeed: 3000,
                }
            },
        {
            breakpoint: 768,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    // dots: true,
                    autoplay: true,
                    utoplaySpeed: 3000,
                }
        },
        {
            breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    dots: true,
                    autoplay: true,
                    autoplaySpeed: 3000,
                }
        },
        {
            breakpoint: 400,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    dots: true,
                    autoplay: true,
                    autoplaySpeed: 3000,
                }
        },
        {
            breakpoint: 300,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    dots: true,
                    autoplay: true,
                    autoplaySpeed: 3000,
                }
        },
        {
            breakpoint: 200,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    dots: true,
                    autoplay: true,
                    autoplaySpeed: 3000,
                }
        },
    ]
});
