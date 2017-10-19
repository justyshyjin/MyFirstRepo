<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Contus\User\Models\SettingCategory;
use Contus\User\Models\Setting;
use Contus\User\Repositories\SettingsRepository;

class AppServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        $this->app ['db']->enableQueryLog ();
        $this->setSettingToConfig ();
    }

    /**
     * Method used to set the config values from cache file.
     *
     * While updating the setting datas from admin side the cache file will be generated.
     *
     * All the setting data stored in JSON format under the storage path
     *
     * @return void
     */
    public function setSettingToConfig() {
        if (Cache::has ( 'settings_caches' )) {
            config ()->set ( 'settings', json_decode ( Cache::get ( 'settings_caches' ), true ) );
            $this->setSessionLifeTimeToConfig ();
        } else {
            $repo = new SettingsRepository ( new Setting (), new SettingCategory () );
            config ()->set ( 'settings', json_decode ( $repo->generateSettingsCache (), true ) );
        }
    }

    /**
     * Method used to set the session lifetime value.
     *
     * Overwrite the session.php lifetime value based on the admin settings
     *
     * @return boolean
     */
    public function setSessionLifeTimeToConfig() {
        $sessionLifetime = config ( 'settings.security-settings.session-settings.session_lifetime' );
        config ()->set ( 'session.lifetime', ($sessionLifetime) ? $sessionLifetime : 120 );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }
}
