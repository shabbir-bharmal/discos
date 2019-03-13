<?php

namespace Packages\QuickBooks;

use Illuminate\Support\ServiceProvider;
use Config;
use App;

class QuickBooksServiceProvider extends ServiceProvider
{
    public function register()
    {        
        require_once(base_path() . '/packages/quickbooks/v3-php-sdk-2.0.5/config.php');
        require_once(PATH_SDK_ROOT . 'Core/ServiceContext.php');
        require_once(PATH_SDK_ROOT . 'DataService/DataService.php');
        require_once(PATH_SDK_ROOT . 'PlatformService/PlatformService.php');
        require_once(PATH_SDK_ROOT . 'Utility/Configuration/ConfigurationManager.php');

        
        App::singleton('quickbooks', function($app)
        {
            $requestValidator = new \OAuthRequestValidator
            (
                Config::get('invoices.AccessToken'),
                Config::get('invoices.AccessTokenSecret'),
                Config::get('invoices.ConsumerKey'),
                Config::get('invoices.ConsumerSecret')
            );

            $realmId = Config::get('invoices.RealmID');

            $dataContext = new \ServiceContext($realmId,
                \IntuitServicesType::QBO, $requestValidator);

            $serviceContext = new \ServiceContext($realmId,
                \IntuitServicesType::IPP, $requestValidator);

            return new \Invoicing(new \DataService($dataContext), new \PlatformService($serviceContext));
        });
    }

}
