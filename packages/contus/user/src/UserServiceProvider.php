<?php

/**
 * User Service Provider is to used to provide a service for user
 *
 * @name UserServiceProvider
 * @vendor Contus
 * @package User
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\User;

use Illuminate\Support\ServiceProvider;
use Contus\Base\Helpers\StringLiterals;

class UserServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @vendor Contus
     *
     * @package User
     * @return void
     */
    public function boot() {
        $user ='user';
        $this->loadTranslationsFrom ( __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $user );

        $this->loadViewsFrom ( __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'views', $user );

        $this->publishes ( [ __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'assets' => public_path ( 'contus/'.$user ) ], $user.'_assets' );

        $this->publishes ( [ __DIR__ . DIRECTORY_SEPARATOR . 'config' => config_path ( 'contus/'.$user ) ], $user.'_config' );

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
     * Method used to share the datas to balde file.
     *
     * Can access getUrl, auth, siteSettings in view files.
     *
     * @return void
     */
    public function shareDataToView() {
        view ()->share ( 'getUserAssetsUrl', function ($url = '/') {
            return url ( config ( 'contus.user.user.vendor' ) . '/' . config ( 'contus.user.user.package' ) . '/' . $url );
        } );
    }
}
