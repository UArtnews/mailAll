var gulp = require('gulp'),
    sys = require('sys'),
    exec = require('child_process').exec,
    behat = require('gulp-behat'),
    jshint = require('jshint'),
    less = require('gulp-less'),
    notify = require('gulp-notify'),
    path = require('path');

gulp.task('phpunit', function() {
    exec('./vendor/bin/phpunit app/tests', function(error, stdout) {
        sys.puts(stdout);
    });
});

gulp.task('behat', function(){
    var options = {
        noSnippets: false,
        colors: false,
        notify: true
    };
    gulp.src('app/tests/**/*.feature')
        .pipe(behat('vendor/bin/behat -f pretty', options))
        .on('error', notify.onError({
            title: "Testing Failed",
            message: "Error(s) occurred during test..."
        }))
        .pipe(notify({
            title: "Testing Passed",
            message: "All tests have passed..."
    }));
//    exec('./vendor/bin/behat', function(error, stdout) {
//        sys.puts(stdout);
//    });
    //test 
});

gulp.task('less', function() {
    return gulp.src([
            './less/bootstrap/bootstrap.less',
            './less/materialDesign/material.less',
            './less/materialDesign/ripples.less',
            './less/materialDesign/material-wfont.less'])
        .pipe(less())
        .pipe(gulp.dest('./public/css'));
});

gulp.task('default', function() {
    gulp.watch('**/*.php', ['behat']);
    gulp.watch('**/*.feature', ['behat']);
    gulp.watch('**/*.less', ['less']);
});