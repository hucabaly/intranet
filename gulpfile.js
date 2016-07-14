var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    // gulp sass
    mix.sass('common/style.scss', 'public/common/css');
    mix.sass('common/login.scss', 'public/common/css');
    
    mix.sass('team/style.scss', 'public/team/css');
    
    mix.sass('sales/sales.scss', 'public/sales/css');
    mix.sass('sales/css_customer.scss', 'public/sales/css');

    // gulp js
    mix.scripts('common/script.js', 'public/common/js');
    
    mix.scripts('team/script.js', 'public/team/js');
    
    mix.scripts('sales/css/analyze.js', 'public/sales/js/css');
    mix.scripts('sales/css/create.js', 'public/sales/js/css');
    mix.scripts('sales/css/dataTables.js', 'public/sales/js/css');
    mix.scripts('sales/css/customer.js', 'public/sales/js/css');
    mix.scripts('sales/css/list.js', 'public/sales/js/css');
    mix.scripts('sales/css/preview.js', 'public/sales/js/css');
    mix.scripts('sales/css/make.js', 'public/sales/js/css');
    mix.scripts('sales/css/welcome.js', 'public/sales/js/css');
    mix.scripts('sales/css/success.js', 'public/sales/js/css');
//    mix.sass('app.scss');
});
