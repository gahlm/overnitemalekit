jQuery(function($) {
	/*  ==========================================================================
		Globals
		========================================================================== */
	report("[•] Future Foam, v01.\n");
	report("[•] Run Globals...");
	var array_row = [
		"section-topnav",
		"section-products",
		"section-capabilities",
		"section-about",
		"section-contact"
	];
	var map_filter_location = "";
	var map_filter_capability = "";
	var map_filter_distro_select = "";
	var map_filter_distro = false;
	report("[•] ...Globals complete.\n");

	/*  ==========================================================================
		Post-DOM Routine
		========================================================================== */
	$(document).ready(function() {
		report("[•] Run Post-DOM Routine...");
		if (window.location.href.indexOf("facilities")) {
			$(".facilities-form #map_location").change(function() {
				report("[m] Location dropdown changed: " + $(this).val());
				map_filter_location = $(this).val();
				filterMap();
				return false;
			});
			$(".facility-us-map a.city").hover(
				function() {
					report("[m] Dot hover on.");
					$(this)
						.find(".title")
						.stop()
						.fadeIn(250);
					return false;
				},
				function() {
					report("[m] Dot hover off.");
					$(this)
						.find(".title")
						.stop()
						.hide();
					return false;
				}
			);
			$(".facility-us-map a.city").click(function() {
				report("[m] Dot clicked: " + $(this).attr("data-city"));
				filterReset();
				$(".facility-us-map .city").addClass("dim");
				$(".facility-us-list .facility-city").hide();
				$(".facility-us-list .facility-city .facility-location-list").hide();
				$(
					".facility-us-list .facility-city .facility-location-list .facility-location"
				).hide();
				$(".facility-international-list").hide();
				var str_class = "." + $(this).attr("data-city");
				$(this).removeClass("dim");
				$(".facility-us-list .facility-city" + str_class).show();
				$(
					".facility-us-list .facility-city" +
						str_class +
						" .facility-location-list"
				).show();
				$(
					".facility-us-list .facility-city" +
						str_class +
						" .facility-location-list .facility-location"
				).show();
				return false;
			});

			$(".facilities-form #capabilities").change(function() {
				report("[m] Capability dropdown changed: " + $(this).val());
				map_filter_capability = $(this).val();
				filterMap();
				return false;
			});

			$(".facilities-form #dc-select").change(function() {
				report("[m] Distro dropdown changed: " + $(this).val());
				map_filter_distro_select = $(this).val();
				filterMap();
				return false;
			});

			// $('.facilities-form #dc').change(function () {
			//   report('[m] Distro checkbox changed:' + $(this).is(':checked'))
			//   map_filter_distro = $(this).is(':checked')
			//   filterMap()
			//   return false
			// })
		}

		report("[•] Post-DOM Routine complete.\n");
	});

	/*  ==========================================================================
		Map Functions
		========================================================================== */
	function filterMap() {
		report(
			"[m] filterMap: [" +
				map_filter_location +
				"][" +
				map_filter_capability +
				"][" +
				map_filter_distro_select +
				"][" +
				map_filter_distro +
				"]"
		);
		// All Off
		$(".facility-us-title").hide();
		$(".facility-us-map").hide();
		$(".facility-us-map .city").addClass("dim");
		$(".facility-us-list").hide();
		$(".facility-us-list .facility-city").hide();
		$(".facility-us-list .facility-city .facility-location-list").hide();
		$(
			".facility-us-list .facility-city .facility-location-list .facility-location"
		).hide();
		$(".facility-international-list").hide();
		$(".facility-international-list .facility-country").hide();
		$(
			".facility-international-list .facility-country .facility-location-list"
		).hide();
		$(
			".facility-international-list .facility-country .facility-location-list .facility-location"
		).hide();

		// Determine Class
		var str_class = "";
		if (
			map_filter_location != "" &&
			map_filter_location != "US-ALL" &&
			map_filter_location != "INT-ALL"
		) {
			str_class += "." + map_filter_location;
		}
		if (map_filter_capability != "") {
			str_class += "." + map_filter_capability;
		}
		if (map_filter_distro_select != "") {
			if (map_filter_distro_select == "filter") {
				str_class += ".distro";
			} else if (map_filter_distro_select == "all") {
				map_filter_location = "";
				map_filter_capability = "";
				$(".facilities-form #map_location").val("");
				$(".facilities-form #capabilities").val("");
				str_class = ".distro";
			}
		}
		if (map_filter_distro == true) {
			str_class += ".distro";
		}

		// Filter On
		if (map_filter_location == "") {
			$(".facility-us-title").show();
			$(".facility-us-map").show();
			$(".facility-us-list").show();
			$(".facility-us-map .city" + str_class).removeClass("dim");
			$(".facility-us-list .facility-location" + str_class)
				.parent()
				.parent()
				.show();
			$(".facility-us-list .facility-location" + str_class)
				.parent()
				.show();
			$(".facility-us-list .facility-location" + str_class).show();
			$(".facility-international-list .facility-location" + str_class)
				.parent()
				.parent()
				.parent()
				.show();
			$(".facility-international-list .facility-location" + str_class)
				.parent()
				.parent()
				.show();
			$(".facility-international-list .facility-location" + str_class)
				.parent()
				.show();
			$(".facility-international-list .facility-location" + str_class).show();
		} else if (map_filter_location.substr(0, 2) == "US") {
			$(".facility-us-title").show();
			$(".facility-us-map").show();
			$(".facility-us-list").show();
			$(".facility-us-map .city" + str_class).removeClass("dim");
			$(".facility-us-list .facility-location" + str_class)
				.parent()
				.parent()
				.show();
			$(".facility-us-list .facility-location" + str_class)
				.parent()
				.show();
			$(".facility-us-list .facility-location" + str_class).show();
		} else if (map_filter_location.substr(0, 3) == "INT") {
			$(".facility-international-list .facility-location" + str_class)
				.parent()
				.parent()
				.parent()
				.show();
			$(".facility-international-list .facility-location" + str_class)
				.parent()
				.parent()
				.show();
			$(".facility-international-list .facility-location" + str_class)
				.parent()
				.show();
			$(".facility-international-list .facility-location" + str_class).show();
		}
	}

	function filterReset() {
		report("[m] filterReset()");
		$(".facility-us-map .city").removeClass("dim");
		$(".facility-us-list .facility-city").show();
		$(".facility-us-list .facility-location").show();
		map_filter_location = "";
		map_filter_capability = "";
		map_filter_distro_select = "";
		map_filter_distro = false;
		$(".facilities-form #map_location").val("");
		$(".facilities-form #capabilities").val("");
		$(".facilities-form #dc-select").val("");
		$(".facilities-form #dc").attr("checked", false);
	}

	function report(str) {
		if (typeof console != "undefined") {
			str += "";
			if (str.substr(0, 3) == "[!]") {
				alert(str);
			} else if (str.substr(0, 1) == "[") {
				console.log(str);
			} else {
				console.log("[ ] " + str);
			}
		}
	}
});
