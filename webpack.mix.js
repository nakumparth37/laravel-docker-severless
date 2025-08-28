const mix = require('laravel-mix');
const dotenv = require('dotenv');
const webpack = require('webpack');
dotenv.config();



mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/users.js', 'public/js')
   .js('resources/js/products.js', 'public/js')
   .js('resources/js/orders.js', 'public/js')
   .js('resources/js/productDetails.js', 'public/js')
   .js('resources/js/bootstrap.js', 'public/js')
   .js('resources/js/notification.js', 'public/js')
   .sourceMaps(); // Add this line if you want source maps

mix.sass('resources/scss/app.scss', 'public/css')
   .sass('resources/scss/admin.scss','public/css')
   .sass('resources/scss/productDetails.scss','public/css')
   .sass('resources/scss/error.scss','public/css');
