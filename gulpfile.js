"use strict";

const { watch, src, dest, series } = require("gulp");
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

// watchers
function watcher() {
	watch(["./assets/styles/**/*.scss"], series(style));
}

// aggregate tasks
exports.style = style;
exports.styleProp = series(clean, styleProduction);
exports.watch = series(style, watcher);
