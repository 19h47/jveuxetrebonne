// BROWSERIFY

var gulp = require('gulp');
var browserify = require('browserify');
var rename = require('gulp-rename');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');
var uglify = require('gulp-uglify');
var util = require('gulp-util');
var config = require('../config' ).browserify;
var bundler = browserify({entries: config.src});

gulp.task('browserify', bundle);

function bundle() {
    return bundler.bundle()
        .on('error', util.log.bind(util, 'Browserify Error'))
        .pipe(source(config.file))
        .pipe(buffer())
        .pipe(gulp.dest(config.dest))
        .pipe(rename({ suffix: '.min' }))
        .pipe(uglify().on('error', util.log))
        .pipe(gulp.dest(config.dest + '/min/'));
}