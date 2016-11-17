const gulp = require('gulp');
const fs = require('fs');
const copydir = require('copy-dir');
const del = require('del');

/*
  GULP cmd
 */

gulp.task('cpybuild', () => {
  del.sync(['public_html/**/*']);
  copydir.sync('old_public/', 'public_html/');
  fs.renameSync('out_dist/index.html', 'resources/views/bootstrap.blade.php');
  copydir.sync('out_dist/', 'public_html/');
  del.sync(['out_dist/**/*']);
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
