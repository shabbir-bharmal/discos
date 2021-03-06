<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useDailyFiles(storage_path().'/logs/laravel.log', Config::get('app.log_level', 'warning'));

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::missing(function($exception)
{
    Log::error('404 thrown for url '. LRequest::url());
    return View::make('errors.missing', ['tag' => '404 - Page not found']);
});

App::error(function(Exception $exception, $code)
{
    if (is_a($exception, 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException')) return;

    Mail::send('emails.webmaster', ['msg' => $exception->getMessage()], function($message)
    {
        $message->to(Config::get('mail.webmaster'), 'webmaster')->subject('Error on '.Config::get('app.url'));
    });

	Log::error($exception);
});


/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return LResponse::make("This site is currently in maintenance mode. Please check again later.", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';
require app_path().'/events.php';
require app_path().'/validators.php';
require base_path().'/app/disco/routes.php';
