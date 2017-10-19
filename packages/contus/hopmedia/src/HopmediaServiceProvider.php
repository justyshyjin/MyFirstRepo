<?php

/**
 * Service Provider for Hopmedia
 *
 * @name HopmediaServiceProvider
 * @vendor Contus
 * @package Hopmedia
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2017 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Hopmedia;

use Illuminate\Support\ServiceProvider;
use Contus\Base\Helpers\StringLiterals;

class HopmediaServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @vendor Contus
     * @pakage hopmedia
     *
     * @return void
     */
    public function boot() {
        $this->loadTranslationsFrom ( __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', 'hopmedia' );
        $this->loadViewsFrom ( __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'views', 'hopmedia' );
        $this->publishes ( [ __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'assets' => public_path ( 'contus/hopmedia' ) ], 'hopmedia_assets' );
        $this->publishes ( [ __DIR__ . DIRECTORY_SEPARATOR . 'config' => config_path ( 'contus/hopmedia' ) ], 'hopmedia_config' );
        $this->shareDataToView ();
    }
    
    /**
     * Register the application services.
     *
     * @vendor Contus
     * @pakage hopmedia
     * 
     * @return void
     */
    public function register() {
        include __DIR__ . '/routes/web.php';
        include __DIR__ . '/routes/api.php';
    }
    
    /**
     * Method used to share the datas to balde file.
     *
     * Can access getUrl, auth, siteSettings in view files.
     *
     * @return void
     */
    public function shareDataToView() {
        view ()->share ( 'getHopmediaAssetsUrl', function ($url = '/') {
            return url ( config ( 'contus.hopmedia.hopmedia.vendor' ) . '/' . config ( 'contus.hopmedia.hopmedia.package' ) . '/' . $url );
        } );
    }
}
