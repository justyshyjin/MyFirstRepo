<?php

/**
 * Service Provider for Payment
 *
 * @name PaymentServiceProvider
 * @vendor Contus
 * @package Payment
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Payment;

use Illuminate\Support\ServiceProvider;
use Contus\Base\Helpers\StringLiterals;

class PaymentServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @vendor Contus
     * @pakage payment
     *
     * @return void
     */
    public function boot() {
        $payment = 'payment';
        $this->loadTranslationsFrom ( __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $payment );
        $this->loadViewsFrom ( __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'views', $payment );
        $this->publishes ( [ __DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'assets' => public_path ( 'contus/'.$payment ) ], $payment.'_assets' );
        $this->publishes ( [ __DIR__ . DIRECTORY_SEPARATOR . 'config' => config_path ( 'contus/'.$payment ) ], 'payment_config' );
        $this->shareDataToView ();
    }
    
    /**
     * Register the application services.
     *
     * @vendor Contus
     * @pakage payment
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
        view ()->share ( 'getPaymentAssetsUrl', function ($url = '/') {
            return url ( config ( 'contus.payment.payment.vendor' ) . '/' . config ( 'contus.payment.payment.package' ) . '/' . $url );
        } );
    }
}
