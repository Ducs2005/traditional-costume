// webpack.mix.js

const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/frontend/js')
   .postCss('resources/css/app.css', 'public/css', [
       //
   ]);
