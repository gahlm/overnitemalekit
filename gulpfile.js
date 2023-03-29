"use strict";

const { watch, src, dest, parallel, series } = require("gulp");
const concat = require("gulp-concat");
const del = require("del");
const maps = require("gulp-sourcemaps");
const sass = require("gulp-sass")(require("sass"));

// clean
function clean() {
	return del("dist");
}

// sass
function style() {
	return src("./assets/styles/application.scss")
		.pipe(maps.init())
		.pipe(sass().on("error", sass.logError))
		.pipe(maps.write())
		.pipe(dest("./dist"));
}

function styleProduction() {
	return src("./assets/styles/application.scss")
		.pipe(sass({ outputStyle: "compressed" }).on("error", sass.logError))
		.pipe(dest("./dist"));
}

// JS
function scripts() {
	return src(["./assets/vendor/**/*.js", "./assets/scripts/*.js"])
		.pipe(maps.init())
		.pipe(concat("application.js"))
		.pipe(maps.write())
		.pipe(dest("./dist/"));
}

// watchers
function watcher() {
	watch(
		["./assets/styles/**/*.scss", "./assets/vendor/**/*.css"],
		series(style)
	);
	watch(["./assets/scripts/*.js"], series(scripts));
}

// aggregate tasks
exports.style = style;
exports.production = series(clean, parallel(styleProduction, scripts));
exports.watch = series(parallel(style, scripts), watcher);
