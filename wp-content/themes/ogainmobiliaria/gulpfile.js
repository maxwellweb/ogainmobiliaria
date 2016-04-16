/*!
 * gulp
 * $ npm install gulp-ruby-sass gulp-autoprefixer gulp-minify-css gulp-jshint gulp-concat gulp-uglify gulp-imagemin gulp-notify gulp-rename gulp-livereload gulp-cache del --save-dev
 */

// Load plugins
var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    minifycss = require('gulp-minify-css'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    cache = require('gulp-cache'),
    livereload = require('gulp-livereload'),
    del = require('del');

// Styles
gulp.task('styles', function() {
  return sass('assets/css/main.sass', { style: 'expanded' })
    .pipe(autoprefixer('last 3 version'))
    .pipe(gulp.dest('assets/css'))
    .pipe(rename({ suffix: '.min' }))
    .pipe(minifycss())
    .pipe(gulp.dest('assets/css'))
    .pipe(notify({ message: 'Styles task complete' }));
});

// Scripts
// gulp.task('scripts', function() {
//   return gulp.src(['assets/js/**/*.js', '!assets/js/**/*.min.js'])
//     .pipe(jshint())
//     .pipe(jshint.reporter('default'))
//     .pipe(concat('main.js'))
//     .pipe(gulp.dest('assets/js'))
//     .pipe(rename({ suffix: '.min' }))
//     .pipe(uglify())
//     .pipe(gulp.dest('assets/js'))
//     .pipe(notify({ message: 'Scripts task complete' }));
// });

// Images
gulp.task('images', function() {
  return gulp.src('assets/img/**/*')
    .pipe(cache(imagemin({ optimizationLevel: 5, progressive: true, interlaced: true })))
    .pipe(gulp.dest('assets/img'))
    .pipe(notify({ message: 'Images task complete' }));
});

// Clean
gulp.task('clean', function() {
  return del(['assets/css', 'assets/img']);
})
// Default task
gulp.task('default', ['clean'], function() {
  gulp.start('styles', 'images');
});

// Watch
gulp.task('watch', function() {

  // Watch .scss files
  gulp.watch('assets/css/**/*.sass', ['styles']);

  // Watch .js files
  // gulp.watch('assets/js/**/*.js', ['scripts']);

  // Watch image files
  gulp.watch('assets/img/**/*', ['images']);

  // Create LiveReload server
  livereload.listen();

  // Watch any files in dist/, reload on change
  gulp.watch(['assets/**']).on('change', livereload.changed);

});
