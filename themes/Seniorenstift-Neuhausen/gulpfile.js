var gulp          = require('gulp');
var browserSync   = require('browser-sync').create();
var $             = require('gulp-load-plugins')();
var autoprefixer  = require('autoprefixer');
var minimist      = require('minimist');

var knownOptions = {
  string: 'env',
  default: {
    env: process.env.NODE_ENV || 'production'
  }
};

var options = minimist(process.argv.slice(2), knownOptions);

var sassPaths = [
  'node_modules/foundation-sites/scss',
  'node_modules/motion-ui/src'
];

function swallowError(error) {

  // If you want details of the error in the console
  console.log(error.toString())

  this.emit('end')
}

// SASS Compiler + autoprefixer
function sass() {
  return gulp.src('scss/template.scss')
    .pipe($.sass({
        includePaths: sassPaths
      })
      .on('error', swallowError))
    .pipe($.postcss([
      autoprefixer({ browsers: ['last 2 versions', 'ie >= 9'] })
    ]))
    .pipe($.cssUrlencodeInlineSvgs())
    .pipe(gulp.dest('css'))
    .pipe(browserSync.stream());
};

// CSS Minify + rename to *.min.css
function minify_css() {
  return gulp.src(['css/template.css'])
    .pipe($.cleanCss())
    .on('error', swallowError)
    .pipe($.rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('css'));
};

// JS Minify + rename to *.min.js
function minify_js() {
  return gulp.src(['js/*.js', '!js/*.min.js'])
    .pipe($.uglify())
    .on('error', swallowError)
    .pipe($.rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('js'));
};

// Static Server + watching scss/html files
function serve() {
  if (options.env === 'wp') {
    browserSync.init({
      proxy: "localhost/wwm"
    });
  } else if (options.env === 'nb') {
    // browserSync disabled
  } else {
    browserSync.init({
      server: {
        baseDir: "./html",
        directory: true,
        routes: {
          "/node_modules": "node_modules",
          "/js": "js",
          "/img": "img",
          "/css": "css"
        },
      }
    });
  }

  gulp.watch("./scss/*.scss", gulp.series('sass', minify_css));
  gulp.watch(["./js/*.js", "!./js/*.min.js"], minify_js);
  gulp.watch(["./html/*.html", "*.php", "./js/*.js", "!./js/*.min.js", "./css/*.css", "!./css/*.min.css"]).on('change', browserSync.reload);

};

// Tasks
gulp.task('sass', sass);
gulp.task('default', gulp.series('sass', minify_css, minify_js, serve));
