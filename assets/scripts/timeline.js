const slide = document.querySelectorAll(".timeline-slide");
const slider = document.querySelector("timeline-slider");
const prevBtn = document.querySelector(".prev");
const nextBtn = document.querySelector(".next");
const first = slide[0];
const last = slide[slide.length - 1];
const wave = document.querySelector(".wave");

window.onload = function() {
	first.classList.remove("next_slide");
	first.classList.add("active");
	last.classList.remove("next_slide");
	last.classList.add("last_slide");
};

const startSlider = type => {
	const active = document.querySelector(".active");
	const last_slide = document.querySelector(".last_slide");
	let next = active.nextElementSibling;
	if (!next) {
		next = first;
	}
	active.classList.remove("active");
	last_slide.classList.remove("last_slide");
	next.classList.remove("next_slide");

	if (type === "prev") {
		active.classList.add("next_slide");
		last_slide.classList.add("active");
		next = last_slide.previousElementSibling;
		if (!next) {
			next = last;
		}
		next.classList.remove("next_slide");
		next.classList.add("last_slide");
		return;
	}

	active.classList.add("last_slide");
	last_slide.classList.add("next_slide");
	next.classList.add("active");
};

let x = 0;
function moveRight() {
	x += -25;
	wave.style.transform = `translateX(${x}%)`;
}
function moveLeft() {
	x += 25;
	wave.style.transform = `translateX(${x}%)`;
}

// let images = [
// 	"http://future-foam.local/wp-content/uploads/2022/01/Loop.svg",
// 	"http://future-foam.local/wp-content/uploads/2022/01/Loop.svg",
// 	"http://future-foam.local/wp-content/uploads/2022/01/Loop.svg"
// ];
// let amountOfImages = images.length;
// let currentIndex = 0;
// console.log(images);
// function updateImage() {
// 	wave.style.backgroundImage = "url('" + images[currentIndex] + "')";
// }
// function prevImage() {
// 	if (currentIndex > 0) {
// 		// Can't go lower than 0.
// 		currentIndex--;
// 		updateImage();
// 	}
// }

// function nextImage() {
// 	if (currentIndex < amountOfImages - 1) {
// 		// Can't go higher than the amount of images present.
// 		currentIndex++;
// 		updateImage();
// 	}
// }

nextBtn.addEventListener("click", () => {
	startSlider();
	// nextImage();
	moveRight();
});
prevBtn.addEventListener("click", () => {
	startSlider("prev");
	// prevImage();
	moveLeft();
});
