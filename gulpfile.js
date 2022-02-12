const gulp = require("gulp");
const sass = require("gulp-sass");
const autoprefixer = require("gulp-autoprefixer");
const watch = require("gulp-watch");

gulp.task("heya", function (done) {
  console.log("I live! Gulp is alive!");
  done();
});

gulp.task("sass", function (done) {
  return gulp
    .src("scss/global.scss")

    .pipe(sass()) // Converts Sass to CSS with gulp-sass
    .pipe(
      autoprefixer({
        cascade: false,
      })
    )
    .pipe(sass({ outputStyle: "compressed" }).on("error", sass.logError))
    .pipe(gulp.dest("css"));
  done();
});

gulp.task("watch", function () {
  watch("scss/*.scss", gulp.series("sass"));
});

// Run all Gulp tasks and serve application
gulp.task("default", gulp.series("sass", "watch"), function () {
  // sass function now incldes the watching of files
});
