<?php

namespace LWS\ExportActions\Tests;

use Illuminate\Support\Facades\Request;
use LWS\ExportActions\Http\Controllers\Exporter;

class ExportTest extends TestCase
{
    public function test_csv()
    {
        $request = Request::create('/store', 'POST', [

            'url' => 'https://jsonplaceholder.typicode.com/photos',
            'format' => 'csv',

        ]);

        $controller = new Exporter();

        $response = $controller->export($request);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
