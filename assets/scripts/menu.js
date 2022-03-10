// listen for products nav item click and toggle mega menu class
const menuButton = document.getElementById("megaMenuTrigger");
menuButton.addEventListener(
	"click",
	function(event) {
		event.preventDefault();
		document
			.getElementById("megaMenu")
			.classList.toggle("header__megaMenu--is-open");
	},
	false
);

// handle nav resizing based on scroll position
const body = document.getElementById("body");
const triggerNav = 120;
document.addEventListener("scroll", function() {
	const pos = window.scrollY;

	const scrollingDownAndInactive =
		pos > triggerNav && !body.classList.contains("header__is-small");
	const scrollingUpAndActive =
		pos < triggerNav && body.classList.contains("header__is-small");

	if (scrollingDownAndInactive) {
		body.classList.add("header__is-small");
	} else if (scrollingUpAndActive) {
		body.classList.remove("header__is-small");
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
