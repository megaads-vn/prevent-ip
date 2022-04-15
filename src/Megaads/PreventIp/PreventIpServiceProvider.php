<?php namespace Megaads\PreventIp;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View as View;

class PreventIpServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{

		$this->package('megaads/prevent-ip');
        include __DIR__.'/../../routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $config = \Config::get('prevent-ip');
        if ($config && isset($config['enable']) && $config['enable'] == true) {
            $checkPath = false;
            if (!empty($config['prevent_paths'])) {
                $path = \Illuminate\Support\Facades\Request::path();
                if (in_array($path, $config['prevent_paths'])) {
                    $checkPath = true;
                }
            }
            $ip = $this->getClientIP();
            $checkIp = false;
            if (!empty($config['allow_ips'])) {
                if (!in_array($ip, $config['allow_ips'])) {
                    $checkIp = true;
                }
            }
            if ($checkPath && $checkIp) {
                View::addNamespace('prevent-ip', base_path('workbench') . '/megaads/prevent-ip/src/views');
                echo View::make('prevent-ip::index', [
                    'ip' => $ip,
                    'url' => \Illuminate\Support\Facades\Request::url()
                ]);
                die;
            }
        }

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

    private function getClientIP() {
        $retVal = 'UNKNOWN';
        if (key_exists("HTTP_CLIENT_IP", $_SERVER))
            $retVal = $_SERVER['HTTP_CLIENT_IP'];
        else if (key_exists("HTTP_X_FORWARDED_FOR", $_SERVER))
            $retVal = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (key_exists("HTTP_X_FORWARDED", $_SERVER))
            $retVal = $_SERVER['HTTP_X_FORWARDED'];
        else if (key_exists("HTTP_FORWARDED_FOR", $_SERVER))
            $retVal = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (key_exists("HTTP_FORWARDED", $_SERVER))
            $retVal = $_SERVER['HTTP_FORWARDED'];
        else if (key_exists("REMOTE_ADDR", $_SERVER))
            $retVal = $_SERVER['REMOTE_ADDR'];
        return $retVal;
    }


}
