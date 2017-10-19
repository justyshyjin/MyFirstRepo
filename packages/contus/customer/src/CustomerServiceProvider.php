<?php

/**
 * Service Provider for Customer
 *
 * @name CustomerServiceProvider
 * @vendor Contus
 * @package Customer
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Customer;

use Illuminate\Support\ServiceProvider;
use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\Facades\View;

class CustomerServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @vendor Contus
     * @pakage Customer
     *
     * @return void
     */
    public function boot() {
        $customer = 'customer';
        $this->loadTranslationsFrom ( __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $customer );
        $this->loadViewsFrom ( __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'views', $customer );
        $this->publishes ( [ __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'assets' => public_path ( 'contus/' . $customer ) ], $customer . '_assets' );
        
        $this->publishes ( [ __DIR__ . DIRECTORY_SEPARATOR . 'config' => config_path ( 'contus/' . $customer ) ], $customer . '_config' );
        
        $this->shareDataToView ();
    }
    
    /**
     * Register the application services.
     *
     * @vendor Contus
     * @pakage Customer
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
        view::share ( 'getCustomerAssetsUrl', function ($url = '/') {
            return url ( config ( 'contus.customer.customer.vendor' ) . '/' . config ( 'contus.customer.customer.package' ) . '/' . $url );
        } );
    }
}
