var gulp = require('gulp');
var sass = require('gulp-sass');

var sourcemaps = require('gulp-sourcemaps');
var browserSync = require('browser-sync').create();
var autoprefixer = require('gulp-autoprefixer');

var rtlcss = require('gulp-rtlcss');
var rename = require('gulp-rename');

var notify = require('gulp-notify');
var handleErrors = function(errorObject, callback) {
  notify.onError(errorObject.toString()).apply(this, arguments);
  if (typeof this.emit === 'function') this.emit('end');
};

gulp.task('sass', function () {
  return gulp.src('./sass/*.scss')
    .pipe(sourcemaps.init())
    .pipe(sass()).on('error', handleErrors)
    .pipe(autoprefixer())
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('css'))
    .pipe(browserSync.reload({ stream: true, match: '**/*.css' }));
});

gulp.task('rtlcss', function () {
  return gulp.src('./css/main.css')
    .pipe(rtlcss())
    .pipe(rename({ suffix: '-rtl' }))
    .pipe(gulp.dest('css'));
});

gulp.task('watch', function() {
  browserSync.init({
    files: ['{template-parts,inc}/**/*.php', '*.php'],
    proxy: 'circle.dev',
    snippetOptions: {
      whitelist: ['/wp-admin/admin-ajax.php'],
      blacklist: ['/wp-admin/**']
    }
  });

  gulp.watch(['sass/**/*'], ['sass', 'rtlcss']);
});

gulp.task('default', ['sass', 'rtlcss', 'watch']);
