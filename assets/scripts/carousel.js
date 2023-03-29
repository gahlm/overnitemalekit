const swiper = new Swiper(".slide-container", {
	slidesPerView: 3,
	spaceBetween: 20,
	sliderPerGroup: 4,
	loop: true,
	centerSlide: "true",
	fade: "true",
	grabCursor: "true",
	pagination: {
		el: ".swiper-pagination",
		clickable: true,
		dynamicBullets: true
	},
	navigation: {
		nextEl: ".swiper-button-next",
		prevEl: ".swiper-button-prev"
	},

	breakpoints: {
		0: {
			slidesPerView: 1
		},
		520: {
			slidesPerView: 2
		},
		768: {
			slidesPerView: 3
		},
		1000: {
			slidesPerView: 4
		}
	}
});
const testimonialSwiper = new Swiper(".testimonial-slide-container", {
	slidesPerView: 1,
	spaceBetween: 20,
	sliderPerGroup: 1,
	loop: true,
	centerSlide: "true",
	fade: "true",
	grabCursor: "true",
	pagination: {
		el: ".swiper-pagination",
		clickable: true,
		dynamicBullets: true
	},
	navigation: {
		nextEl: ".swiper-button-next",
		prevEl: ".swiper-button-prev"
	},

	breakpoints: {
		0: {
			slidesPerView: 1
		},
		520: {
			slidesPerView: 1
		},
		768: {
			slidesPerView: 1
		},
		1000: {
			slidesPerView: 1
		}
	}
});
