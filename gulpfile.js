/**
 * Gulpfile for web_checker
 */

'use strict';

/**
 * Config
 */
const sass_conf = {
  outputStyle: 'nested',
  includePaths: [
    'web/lib/font-awesome/scss',
  ],
  precision: 10,
  errLogToConsole: true,
};
const prefix_conf = {
  browsers: ['last 2 versions', 'ie > 8'],
};

/**
 * Load plugins
 */

// Gulp and utilities
const gulp = require('gulp-help')(require('gulp'));
const u = require('gulp-util');
const log = u.log;
const c = u.colors;
const del = require('del');
const plumber = require('gulp-plumber');
const argv = require('yargs').default('production', false).argv;
const watch = require('gulp-watch');
const gulpif = require('gulp-if');

// SASS
const sass = require('gulp-sass');
const sourcemaps = require('gulp-sourcemaps');
const prefix = require('gulp-autoprefixer');
const clean = require('gulp-clean-css');
const globbing = require('gulp-css-globbing');

// JavaScript
const fs = require('fs');
const path = require('path');
const merge = require('merge-stream');
const concat = require('gulp-concat');
const babel = require('gulp-babel');
const rename = require('gulp-rename');
const uglify = require('gulp-uglify');

// Images
const imagemin = require('gulp-imagemin');

// Browser Sync
const bs = require('browser-sync').create();
const findParentDir = require('find-parent-dir');

// Check environment
let env = 'dev';
if (argv.production) {
  env = 'prod';
  sass_conf.outputStyle = 'compressed';
}
function is_dev() {
  return env === 'dev';
}

/**
 * Error handler
 */
function onError(err) {

  log(c.red(err.message));
  if (err.message !== 'undefined') {
    log(c.red(`Error: ${err.message}`));
  } else if (err.messageFormatted) {
    // Send the error to the console.
    log(c.red(`Error in file: ${err.messageFormatted}`));
  }
  this.emit('end');
}

/**
 * Get sub directories in dir
 */
function getDirs(dir) {

  return fs.readdirSync(dir)
      .filter(function(file) {
        return fs.statSync(path.join(dir, file)).isDirectory();
      });
}

/**
 * Compile SASS
 */
gulp.task('sass', 'Compile SASS', () => {

  return gulp.src([
        'assets/sass/**/*.scss',
      ])
      .pipe(plumber({
        errorHandler: onError,
      }))
      .pipe(globbing({
        extensions: ['.scss'],
      }))
      .pipe(gulpif(is_dev, sourcemaps.init()))
      .pipe(sass(sass_conf))
      .pipe(prefix(prefix_conf))
      .pipe(gulpif(!is_dev, uglify()))
      .pipe(gulpif(is_dev, sourcemaps.write()))
      .pipe(gulp.dest('web/css'))
      .pipe(bs.stream({match: '**/*.css'}));
});

/**
 * Delete compiled CSS
 */
gulp.task('clean-css', 'Delete all the CSS files in the ./web/css directory', () => {

  return del(['web/css/*.css'], (err) => {
    if (err) {
      log(c.red('clean-css'), err);
    } else {
      log(c.green('clean-css'), 'deleted old stylesheets');
    }
  });
});

/**
 * Transpile, minify and concatenate Javascript files in scripts directory,
 * creating separate files for everything in each sub directory.
 * Service workers need to live in root, anything in sw dir goes there.
 */
gulp.task('scripts', () => {

  const scriptsPath = 'assets/js';
  const dirs = getDirs(scriptsPath);

  const is_sw = function(dir) {
    return dir === 'sw';
  };

  const tasks = dirs.map((dir) => {
    return gulp.src(path.join(scriptsPath, dir, '/**/*.js'))
        .pipe(gulpif(is_dev, sourcemaps.init()))
        .pipe(plumber({
          errorHandler: onError,
        }))
        .pipe(babel({ presets: ['es2015'] }))
        .pipe(concat(dir + '.js'))
        .pipe(gulpif(is_sw(dir), gulp.dest('./web'), gulp.dest('./web/js')))
        .pipe(uglify())
        .pipe(rename(dir + '.min.js'))
        .pipe(gulpif(is_dev, sourcemaps.write()))
        .pipe(gulpif(is_sw(dir), gulp.dest('./web'), gulp.dest('./web/js')))
  });

  const root = gulp.src(scriptsPath + '/*.js')
      .pipe(gulpif(is_dev, sourcemaps.init()))
      .pipe(plumber({
        errorHandler: onError,
      }))
      .pipe(babel({ presets: ['es2015'] }))
      .pipe(concat('scripts.js'))
      .pipe(gulp.dest('./web/js'))
      .pipe(rename('scripts.min.js'))
      .pipe(uglify())
      .pipe(gulpif(is_dev, sourcemaps.write()))
      .pipe(gulp.dest('./web/js'));

  return merge(tasks, root)
      .pipe(bs.stream({ match: '**/*.js' }));
});

/**
 * Compress images
 */
gulp.task('images', 'Compress image in ./images directory', () => {

  return gulp.src('assets/images/**/*.+(gif|jpeg|jpg|png|svg)')
      .pipe(plumber({
        errorHandler: onError,
      }))
      .pipe(imagemin({
        plugins: [
          { removeDoctype: false },
        ]}))
      .pipe(gulp.dest('./web/img'));
});

/**
 * Set up Browser Sync
 */
gulp.task('bs', 'Set Browser Sync to serve the ./web directory', () => {

  bs.init({
    proxy: path.basename(findParentDir.sync(__dirname, 'web')),
    open: false,
  });
});

/**
 * Watch SASS directory for changes.
 */
gulp.task('watch-sass', () => {

  watch('assets/sass/**/*.scss', { verbose: true, usePolling: true, useFsEvents: true }, () => {
    gulp.start('sass');
  });
});

/**
 * Watch JavaScript directory for changes.
 */
gulp.task('watch-scripts', () => {

  watch('assets/js/**/*.js', { verbose: true, usePolling: true, useFsEvents: true }, () => {
    gulp.start('scripts');
  });
});

/**
 * Watch images directory for changes.
 */
gulp.task('watch-images', () => {

  watch('assets/images/**/*.svg', { verbose: true, usePolling: true, useFsEvents: true },() => {
    gulp.start('images');
  });
});

/**
 * Default task - compile and watch SASS, JavaScript and images
 */
gulp.task('default', false, ['bs', 'scripts', 'images', 'sass', 'watch-scripts', 'watch-images', 'watch-sass']);
