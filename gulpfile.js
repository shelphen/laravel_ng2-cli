const gulp = require('gulp');
const elixir = require('laravel-elixir');

const fs = require('fs');
const copydir = require('copy-dir');
const del = require('del');

/*
  GULP cmd
 */

gulp.task('clearpublic', () => {
  return del.sync(['public_html/**/*']);
});

gulp.task('cpypublic', () => {
  return copydir.sync('old_public/', 'public_html/');
});

gulp.task('cpybootstrap', () => {
  return fs.renameSync('out_dist/index.html', 'resources/views/bootstrap.blade.php');
});

gulp.task('cpydist', () => {
  return copydir.sync('out_dist/', 'public_html/');
});

gulp.task('cleardist', () => {
  return del.sync(['out_dist/**/*']);
});

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

elixir((mix) => {

});