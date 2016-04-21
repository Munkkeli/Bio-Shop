var gulp = require('gulp');
var less = require('gulp-less');
var jade = require('gulp-jade-php');
var cssnano = require('gulp-cssnano');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var sourcemaps = require('gulp-sourcemaps');
var header = require('gulp-header');
var environments = require('gulp-environments');
var ext_replace = require('gulp-ext-replace');

var development = environments.development;
var production = environments.production;

var pkg = require('./package.json');
var banner = ['/**',
  ' * <%= pkg.name %> - <%= pkg.description %>',
  ' * @version v<%= pkg.version %>',
  ' * @link <%= pkg.homepage %>',
  ' * @license <%= pkg.license %>',
  ' */',
  ''].join('\n');

var destination = './build';

var paths = {
  xml: ['./assets/xml/*.xml'],
  php: ['./assets/php/*.php'],
	less: ['./assets/less/*.less'],
	js: ['./assets/js/*.js'],
  views: ['./views/*.jade', '!./views/layout.jade'],
	plugins: ['./assets/js/plugins/*.js'],
	css: ['./assets/css/*.css'],
	fonts: ['./assets/fonts/*.*'],
  images: ['./assets/images/*.*'],
  assets: ['./assets/*.*']
};

gulp.task('xml', function() {
  return gulp.src(paths.xml)
    .pipe(gulp.dest(destination + '/xml'));
});

gulp.task('php', function() {
  return gulp.src(paths.php)
    .pipe(gulp.dest(destination + '/php'));
});

gulp.task('less', function() {
  return gulp.src(paths.less)
    .pipe(less())
    .pipe(cssnano())
    .pipe(header(banner, { pkg : pkg }))
    .pipe(rename(function (path) {
	    path.extname = '.min.css';
	  }))
    .pipe(gulp.dest(destination + '/css'));
});

gulp.task('js', function() {
  return gulp.src(paths.js)
    .pipe(development(sourcemaps.init()))
    .pipe(uglify())
    .pipe(header(banner, { pkg : pkg }))
    .pipe(rename(function (path) {
	    path.extname = '.min.js';
	  }))
    .pipe(development(sourcemaps.write('./')))
    .pipe(gulp.dest(destination + '/js'));
});

gulp.task('views', function() {
  gulp.src(paths.views)
    .pipe(jade())
    .pipe(ext_replace('.php'))
    .pipe(gulp.dest(destination))
});

gulp.task('plugins', function() {
  return gulp.src(paths.plugins)
  	.pipe(concat('plugins.min.js'))
    .pipe(gulp.dest(destination + '/js'));
});

gulp.task('css', function() {
  return gulp.src(paths.css)
  	.pipe(concat('plugins.min.css'))
    .pipe(gulp.dest(destination + '/css'));
});

gulp.task('fonts', function() {
  return gulp.src(paths.fonts)
    .pipe(gulp.dest(destination + '/fonts'));
});

gulp.task('images', function() {
  return gulp.src(paths.images)
    .pipe(gulp.dest(destination + '/images'));
});

gulp.task('assets', function() {
  return gulp.src(paths.assets)
    .pipe(gulp.dest(destination));
});

gulp.task('watch', function() {
  gulp.watch(paths.less, ['less']);
  gulp.watch(paths.js, ['js']);
  gulp.watch(['./views/*.jade'], ['views']);
});


gulp.task('default', ['xml','php','less', 'js', 'views', 'plugins', 'css', 'fonts', 'images', 'assets']);
