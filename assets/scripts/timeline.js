// Slider functionality for the history page. Adds and removes classes within the twig for loop using active, next and last to loop through slide content.
// access variables

const slide = document.querySelectorAll(".timeline-slide");
const slider = document.querySelector("timeline-slider");
const prevBtn = document.querySelector(".prev");
const nextBtn = document.querySelector(".next");
const first = slide[0];
const last = slide[slide.length - 1];
const wave = document.querySelector(".wave");
const firstDot = document.querySelector("#dot1");
const thirdDot = document.querySelector("#dot3");
// add active and last classes
window.onload = function() {
	if (!!first && !!last) {
		first.classList.remove("next_slide");
		first.classList.add("active");
		last.classList.remove("next_slide");
		last.classList.add("last_slide");
	}
};
// handle sliding classes along loop
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
// sliding the wave animation along the loop
function moveRight() {
	wave.classList.toggle("play");
	setTimeout(function() {
		wave.classList.toggle("play");
	}, 700);
}
function moveLeft() {
	wave.classList.toggle("playBackward");
	setTimeout(function() {
		wave.classList.toggle("playBackward");
	}, 700);
}
// chevron function calls
if (!!nextBtn) {
	nextBtn.addEventListener("click", () => {
		startSlider();
		moveRight();
	});
}
if (!!prevBtn) {
	prevBtn.addEventListener("click", () => {
		startSlider("prev");
		moveLeft();
	});
}
// dot function calls
if (!!firstDot) {
	firstDot.addEventListener("click", () => {
		startSlider("prev");
		moveLeft();
	});
}
if (!!thirdDot) {
	thirdDot.addEventListener("click", () => {
		startSlider();
		moveRight();
	});
}
