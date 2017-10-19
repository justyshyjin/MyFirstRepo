<?php

/**
 * Video Service Provider which defines all information about the video package.
 *
 * @name VideoServiceProvider
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video;

use Illuminate\Support\ServiceProvider;
use Contus\Base\Helpers\StringLiterals;

class VideoServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @vendor Contus
     *
     * @package Video
     * @return void
     */
    public function boot() {
        $video = 'video';
        $this->loadTranslationsFrom ( __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $video );
        $this->loadViewsFrom ( __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'views', $video );
        
        $this->publishes ( [ __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'assets' => public_path ( 'contus/'.$video ) ], $video.'_assets' );
        
        $this->publishes ( [ __DIR__ . DIRECTORY_SEPARATOR . 'config' => config_path ( 'contus/'.$video ) ], $video.'_config' );
        
        $this->shareDataToView ();
    }
    
    /**
     * Register the application services.
     *
     * @vendor Contus
     *
     * @package User
     * @return void
     */
    public function register() {
        include __DIR__ . '/routes/web.php';
        include __DIR__ . '/routes/api.php';
    }
    
    /**
     * Method used to share the data to blade file.
     *
     * Can access getUrl, auth, siteSettings in view files.
     *
     * @return void
     */
    public function shareDataToView() {
        view ()->share ( 'getVideoAssetsUrl', function ($url = '/') {
            return url ( config ( 'contus.video.video.vendor' ) . '/' . config ( 'contus.video.video.package' ) . '/' . $url );
        } );
    }
}
