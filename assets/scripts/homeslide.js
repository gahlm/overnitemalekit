const colorword = document.querySelector("#colorword");
const words = ["flowing.", "growing.", "optimized.", "targeted."];

let count = 0;
function rotateWords() {
	if (count < words.length) {
		colorword.textContent = words[count];
		count++;
		return count;
	} else {
		count = 0;
		colorword.textContent = words[count];
		return count;
	}
}

setInterval(rotateWords, 5000);
