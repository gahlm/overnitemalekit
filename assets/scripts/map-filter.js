jQuery(function($) {
	//Filter Locations
	$("select.map_location").change(function() {
		var filter = $(this).val();
		filterLoc(filter);
	});

	// Locations filter function
	function filterLoc(value) {
		var list = $(".facilities .facility-location");
		$(list).hide();
		if (value == "all") {
			$(".facilities")
				.find("article")
				.each(function(i) {
					$(this).show();
				});
			// $(".international")
			// 	.find("article")
			// 	.each(function(i) {
			// 		$(this).show();
			// 	});
		} else if (value == "INT-ALL") {
			$(".facilities .facility-location").hide();
			$(".international .facility-location").show();
		} else {
			// *=" means that if a data-custom type contains multiple values, it will find them
			$(".facilities")
				.find(`article[class~=${value}]`)
				.each(function(i) {
					$(this).show();
				});
		}
	}

	//Filter Capabilities
	$("select.capabilities").change(function() {
		var filterCap = $(this).val();
		filterCapabilites(filterCap);
	});

	// Capabilites filter function
	function filterCapabilites(value) {
		var capList = $(".facilities .facility-location");
		$(capList).hide();
		if (value == "all") {
			$(".facilities")
				.find("article")
				.each(function(i) {
					$(this).show();
				});
		} else {
			// *=" means that if a data-custom type contains multiple values, it will find them
			$(".facilities")
				.find(`article[class~=${value}]`)
				.each(function(i) {
					$(this).show();
				});
		}
	}
	//Filter DC-Filter
	$("select.dc-select").change(function() {
		var filterDC = $(this).val();
		DCFilter(filterDC);
	});

	// Capabilites filter function
	function DCFilter(value) {
		var DCList = $(".facilities .facility-location");
		$(DCList).hide();
		if (value == "all") {
			$(".facilities")
				.find("article")
				.each(function(i) {
					$(this).show();
				});
		} else {
			// *=" means that if a data-custom type contains multiple values, it will find them
			$(".facilities")
				.find(`article[class~=${value}]`)
				.each(function(i) {
					$(this).show();
				});
		}
	}
});
