const mix = require('laravel-mix');

// Compile the main JavaScript file
mix.js('resources/js/app.js', 'public/js')
    // Compile the main css
    .css('resources/css/app.scss', 'public/css')
    .version();
