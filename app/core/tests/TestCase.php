<?php

use \Illuminate\Http\Response;

# configuration setup
# seed data        
# do the action
# test the result

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__ . '/../../../bootstrap/start.php';
	}
    
    private function prepareForTests()
    {
        Artisan::call('migrate');
        #Artisan::call('db:seed');
        Mail::pretend(true);
    }
    
    public function setUp()
    {
        parent::setUp(); // Don't forget this!

        $this->prepareForTests();
    }

    public function responseHasError(Response $response, $key = false)
    {
        $reply = $this->get_api_reply($response);
        return $key ? isset($reply->data->$key) : $reply->http != 200;
    }

    protected function get_api_reply(Response $response)
    {
        return json_decode($response->getContent(), false);
    }

}
