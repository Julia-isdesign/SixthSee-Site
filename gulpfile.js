
// Dependencies

const { series, parallel } = require("gulp");
const { src, dest } = require("gulp");

const convertEncoding = require("gulp-convert-encoding");
const cssconcat = require("gulp-concat-css");
const cssmin = require("gulp-cssmin");
const del = require("delete");
const rename = require("gulp-rename");
const uglify = require("gulp-uglify");


// CSS

function css_reset(e) {
	del("site/css/geral/*.css", e);
	del("site/css/paginas/*.min.css", e);
}

function css_bundler() {
	return src([
		"site/css/_basicos/*.css",
		"site/css/_blocos/*.css",
		"site/css/_componentes/*.css",
	])
		.pipe(cssconcat("estilos.min.css"))
		.pipe(cssmin())
		.pipe(dest("site/css/geral/"));
}

function css_paginas() {
	return src("site/css/paginas/*.css")
		.pipe(cssmin())
		.pipe(rename(function(path) {
			return {
				dirname: path.dirname,
				basename: path.basename,
				extname: ".min" + path.extname
			};
		}))
		.pipe(dest("site/css/paginas/"));
}


// JS

function js_reset(e) {
	del("site/js/efeitos/*.min.js", e);
	del("site/js/paginas/*.min.js", e);
}

function js_efeitos() {
	return src("site/js/efeitos/*.js")
		.pipe(convertEncoding({from:"ISO-8859-1", to:"UTF-8"}))
		.pipe(uglify())
		.pipe(rename(function(path) {
			return {
				dirname: path.dirname,
				basename: path.basename,
				extname: ".min" + path.extname
			};
		}))
		.pipe(convertEncoding({from:"UTF-8", to:"ISO-8859-1"}))
		.pipe(dest("site/js/efeitos/"));
}

function js_paginas() {
	return src("site/js/paginas/*.js")
		.pipe(convertEncoding({from:"ISO-8859-1", to:"UTF-8"}))
		.pipe(uglify())
		.pipe(rename(function(path) {
			return {
				dirname: path.dirname,
				basename: path.basename,
				extname: ".min" + path.extname
			};
		}))
		.pipe(convertEncoding({from:"UTF-8", to:"ISO-8859-1"}))
		.pipe(dest("site/js/paginas/"));
}


// Export

exports.css = series(
	css_reset,
	css_bundler,
	css_paginas
);

exports.js = series(
	js_reset,
	js_efeitos,
	js_paginas
);

exports.default = parallel(exports.css, exports.js);
