<?php

/**
 * Service Provider for Base
 *
 * @name       BaseServiceProvider
 * @vendor     Contus
 * @package    Base
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Base;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\Facades\View;


class BaseServiceProvider extends ServiceProvider{
    /**
     * Bootstrap the application services.
     *
     * @vendor Contus
     * @package Base
     * @return void
     */
    public function boot(){
        $this->loadTranslationsFrom(__DIR__.DIRECTORY_SEPARATOR.StringLiterals::RESOURCES.DIRECTORY_SEPARATOR.'lang', 'base');

        $this->loadViewsFrom(__DIR__.DIRECTORY_SEPARATOR.StringLiterals::RESOURCES.DIRECTORY_SEPARATOR.'views', 'base');

        $this->publishes([__DIR__.DIRECTORY_SEPARATOR.StringLiterals::RESOURCES.DIRECTORY_SEPARATOR.'assets' => public_path('contus/base'),], 'base_assets');
        $this->publishes([__DIR__.DIRECTORY_SEPARATOR.StringLiterals::RESOURCES.DIRECTORY_SEPARATOR.'assets' => base_path('resources/assets'),], 'resource_assets');

         $this->publishes([__DIR__.DIRECTORY_SEPARATOR.'config' => config_path('contus/base'),], 'base_config');

        $this->shareDataToView ();
    }
    /**
     * Register the application services.
     *
     * @vendor Contus
     * @package Base
     * @return void
     */
    public function register(){
    }

    /**
     * Method used to share the datas to balde file.
     *
     * Can access getUrl, auth, siteSettings in view files.
     *
     * @return void
     */
    public function shareDataToView() {

        view::share ( 'isRouteActive', function ($routePath) {
            $class = 'nav-active';
            if (! is_array ( $routePath )) {
                $routePath = [ 
                        $routePath 
                ];
                $class = 'active';
            }
            foreach ( $routePath as $value ) {
                if ((str_is ( "$value/*", url()->current() ) || str_is ( "$value", url()->current() ))) {
                    return $class;
                }
            }
        } );
        
        View::share ( 'getFormattedPrice', function ($price) {
            return config ( 'app.currencySymbol' ) . ' ' . number_format ( $price, 2 );
        } );
        
        View::share ( 'auth', app ()->make ( 'auth' ) );        
        View::share ( 'siteSettings', config ()->get ( 'settings' ) );
        View::share ( 'getBaseAssetsUrl', function ($url= '/') {
            return url(config('contus.base.base.vendor').'/'.config('contus.base.base.package').'/'.$url);
        } );
            View::share ( 's3BucketUrl', function ($url= '/') {
                return url(config('settings.aws-settings.aws-general.aws_s3_image_base_url').'/'.$url);
            } );
            View::share ( 'cdnUrl', function ($url= '/') {
               return url(config('settings.aws-settings.aws-general.aws_s3_image_base_url_mobile').'/'.$url);
            } );
    }
}
