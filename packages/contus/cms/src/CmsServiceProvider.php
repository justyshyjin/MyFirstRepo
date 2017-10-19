<?php

/**
 * Service Provider for Cms
 *
 * @name CmsServiceProvider
 * @vendor Contus
 * @package cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms;

use Illuminate\Support\ServiceProvider;
use Contus\Base\Helpers\StringLiterals;

class CmsServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @vendor Contus
     * @pakage Cms
     *
     * @return void
     */
    public function boot() {
        $cms = 'cms';
        $this->loadTranslationsFrom ( __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $cms );
        
        $this->loadViewsFrom ( __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'views', $cms );
        
        $this->publishes ( [ __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'assets' => public_path ( 'contus/'.$cms ) ], $cms.'_assets' );
        
        $this->publishes ( [ __DIR__ . DIRECTORY_SEPARATOR . 'config' => config_path ( 'contus/'.$cms ) ], $cms.'_config' );
        
        $this->shareDataToView ();
    }
    
    /**
     * Register the application services.
     *
     * @vendor Contus
     * @pakage Cms
     *
     * @return void
     */
    public function register() {
        include __DIR__ . '/routes/api.php';
        include __DIR__ . '/routes/web.php';
    }
    
    /**
     * Method used to share the datas to balde file.
     *
     * Can access getUrl, auth, siteSettings in view files.
     *
     * @return void
     */
    public function shareDataToView() {
        view ()->share ( 'getCmsAssetsUrl', function ($url = '/') {
            return url ( config ( 'contus.cms.cms.vendor' ) . '/' . config ( 'contus.cms.cms.package' ) . '/' . $url );
        } );
    }
}
