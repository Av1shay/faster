// Defining base pathes
var basePaths = {
    js: './js/',
    node: './node_modules/',
    dev: './src/'
};

// browser-sync watched files
// automatically reloads the page when files changed
var browserSyncWatchFiles = [
    './css/*.min.css',
    './js/*.min.js',
    './**/*.php'
];

var browserSyncOptions = {
    proxy: "Root path should be placed here",
    notify: false,
    port: 8080
};

const gulp = require('gulp');
const plumber = require('gulp-plumber');
const sass = require('gulp-sass');
const watch = require('gulp-watch');
const gulpSequence = require('gulp-sequence');
const cleanCSS = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');
const del = require('del');
const browserSync = require('browser-sync').create();
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const imagemin = require('gulp-imagemin');


/* Take all scss files in the folder /scss/, and compile theme to css into the folder /css/ */
gulp.task('sass', function () {
    return gulp.src('./scss/*.scss')
        .pipe(plumber({
            errorHandler: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(sass())
        .pipe(gulp.dest('./css'))
});

gulp.task('browser-sync', function() {
    browserSync.init(browserSyncWatchFiles, browserSyncOptions);

});

/* minify faster.css file to faster.min.css file */
gulp.task('minify-css', function() {
    return gulp.src('./css/faster.css')
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(cleanCSS({compatibility: '*'}))
        .pipe(plumber({
            errorHandler: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(rename({suffix: '.min'}))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('./css/'));
});

/* images minify */
gulp.task('imagemin', function () {
    gulp.src('src/images/*')
        .pipe(imagemin())
        .pipe(gulp.dest('images'))
});

/* css watcher */
gulp.task('watch', function () {
    gulp.watch('./scss/**/*.scss', ['styles']);
    gulp.watch([basePaths.dev + 'js/**/*.js','js/**/*.js','!js/faster.js','!js/faster.min.js', '!js/admin.js'], ['scripts']);
});

// Starts watcher with browser-sync.
gulp.task('watch-bs', ['browser-sync', 'watch'], function () { });

// Uglifies and concat all JS files into one
gulp.task('scripts', function() {
    var scripts = [
        basePaths.dev + 'js/tether/tether.js',
        basePaths.dev + 'js/bootstrap4/bootstrap.js',
        './js/skip-link-focus-fix.js'
    ];
    gulp.src(scripts)
        .pipe(concat('faster.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./js/'));

    gulp.src(scripts)
        .pipe(concat('faster.js'))
        .pipe(gulp.dest('./js/'));
});

gulp.task('admin-scripts', function() {
    var scripts = [
        './js/admin.js'
    ];
    gulp.src(scripts)
        .pipe(concat('admin.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./js/'));

    gulp.src(scripts)
        .pipe(concat('admin.js'))
        .pipe(gulp.dest('./js/'));
});

gulp.task('styles', function(callback){ gulpSequence('sass', 'minify-css')(callback)});

// Delete all files in the src folder
gulp.task('clean-source', function () {
    return del(['src/**/*','!src/images']);
});

///////////////// Copy assets ///////////////////////
gulp.task('copy-assets', ['clean-source'], function() {

    // Copy bootstrap js
    var stream = gulp.src(basePaths.node + 'bootstrap/dist/js/**/*.js')
        .pipe(gulp.dest(basePaths.dev + '/js/bootstrap4'));

    // Copy bootstrap css
    gulp.src(basePaths.node + 'bootstrap/scss/**/*.scss')
        .pipe(gulp.dest(basePaths.dev + '/scss/bootstrap4'));

    // Copy all Font Awesome Fonts
    gulp.src(basePaths.node + 'font-awesome/fonts/**/*.{ttf,woff,woff2,eof,svg,otf,eot}')
        .pipe(gulp.dest('./assets/fonts'));

    // Copy all Font Awesome SCSS files
    gulp.src(basePaths.node + 'font-awesome/scss/*.scss')
        .pipe(gulp.dest(basePaths.dev + '/scss/fontawesome'));

    // Copy tether js
    gulp.src(basePaths.node + 'tether/dist/js/**/*.js')
        .pipe(gulp.dest(basePaths.dev + '/js/tether'));

    return stream;
});
