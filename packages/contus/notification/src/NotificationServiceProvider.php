<?php

/**
 * Service Provider for Notification
 *
 * @name NotificationServiceProvider
 * @vendor Contus
 * @package Notification
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Notification;

use Illuminate\Support\ServiceProvider;
use Contus\Base\Helpers\StringLiterals;

class NotificationServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @vendor Contus
     * @pakage notification
     *
     * @return void
     */
    public function boot() {
        $this->loadTranslationsFrom ( __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', 'notification' );
        $this->loadViewsFrom ( __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'views', 'notification' );
        $this->publishes ( [ __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'assets' => public_path ( 'contus/notification' ) ], 'notification_assets' );
        $this->publishes ( [ __DIR__ . DIRECTORY_SEPARATOR . 'config' => config_path ( 'contus/notification' ) ], 'notification_config' );
        $this->shareDataToView ();
    }
    
    /**
     * Register the application services.
     *
     * @vendor Contus
     * @pakage notification
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
        view ()->share ( 'getnotificationAssetsUrl', function ($url = '/') {
            return url ( config ( 'contus.notification.notification.vendor' ) . '/' . config ( 'contus.notification.notification.package' ) . '/' . $url );
        } );
    }
}
