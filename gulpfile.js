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