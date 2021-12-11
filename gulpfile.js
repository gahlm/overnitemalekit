"use strict";

require("dotenv").config();

const { watch, src, dest, series } = require("gulp");
// const del = require("del");
const maps = require("gulp-sourcemaps");
const sass = require("gulp-sass")(require("sass"));
const browserSync = require("browser-sync").create();

// clean
// function clean() {
// 	return del("dist");
// }

// sass
function style() {
	return src("./assets/styles/application.scss")
		.pipe(maps.init())
		.pipe(sass().on("error", sass.logError))
		.pipe(maps.write())
		.pipe(dest("./dist"))
		.pipe(browserSync.stream());
}

function styleProduction() {
	return src("./assets/styles/application.scss")
		.pipe(sass({ outputStyle: "compressed" }).on("error", sass.logError))
		.pipe(dest("./dist"));
}

// watchers
function watcher() {
	browserSync.init({
		proxy: `localhost:${process.env.PORT}`,
		open: "local",
		port: process.env.PORT,
		liveReload: true,
	});
	watch(["./assets/styles/**/*.scss"], series("style"));
	watch(["./assets/scripts/**/*.js", "./**/*.php", "./**/*.twig"]).on(
		"change",
		browserSync.reload
	);
}

// aggregate tasks
exports.style = style;
exports.styleProp = styleProduction;
exports.watch = series(style, watcher);
