document.addEventListener("DOMContentLoaded", function() {
	const sustainNum = document.querySelector(".sustainability__number");
	let rate = sustainNum.dataset.rate;
	let orgDate = new Date(2010, 1, 1, 00, 00, 0);
	let orgTime = orgDate.getTime();
	let orgVal = 1613760000;

	async function sustainability() {
		let today = new Date().getTime();
		let t = today - orgTime;
		let diff = t / (1000 * 60);
		let currentSustain = Math.round(diff * rate);
		sustainNum.innerHTML = `${currentSustain.toLocaleString("en-US")} lbs.`;
	}
	let countup = setInterval(sustainability, 1000);
	sustainability();
});
