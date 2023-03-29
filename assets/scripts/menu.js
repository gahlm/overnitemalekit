// handle nav resizing based on scroll position
const body = document.getElementById("body");
const header = document.querySelector("header");
const triggerNav = 120;
document.addEventListener("scroll", function() {
	const pos = window.scrollY;

	const scrollingDownAndInactive =
		pos > triggerNav && !body.classList.contains("header__is-small");
	const scrollingUpAndActive =
		pos < triggerNav && body.classList.contains("header__is-small");

	if (scrollingDownAndInactive) {
		body.classList.add("header__is-small");
		header.classList.add("header-slide");
	} else if (scrollingUpAndActive) {
		body.classList.remove("header__is-small");
		header.classList.remove("header-slide");
	} else {
		return;
	}
});

const navToggle = document.querySelector(".nav-toggle");
const mobileNav = document.querySelector(".mobile-nav");

navToggle.addEventListener("click", function() {
	mobileNav.classList.toggle("toggled");
	navToggle.classList.toggle("move");
});
