const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js').postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
])
.copyDirectory('resources/views/assets/plugins/','public/assets/plugins')
.copyDirectory('resources/views/assets/imgs/','public/assets/imgs')

.styles('resources/views/assets/css/painel.css','public/assets/css/painel.css')
.styles('resources/views/assets/css/site.css','public/assets/css/site.css')

.js('resources/views/assets/js/produtos.js','public/assets/js/produtos.js')
.js('resources/views/assets/js/scriptAssinatura.js','public/assets/js/scriptAssinatura.js')
.js('resources/views/assets/js/scripts.js','public/assets/js/scripts.js')
.js('resources/views/assets/js/site.js','public/assets/js/site.js')
;
