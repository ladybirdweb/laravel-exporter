<?php

namespace LWS\ExportActions\Jobs;

use PDF;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use SoapBox\Formatter\Formatter;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class WriteCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;
    

    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->requestData = $request->all();
        
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();
        

        $request = new \GuzzleHttp\Psr7\Request('GET', $this->requestData->url);
        $promise = $client->sendAsync($request)->then(function ($responseData) {

            $formatter = Formatter::make($responseData->getBody(), Formatter::JSON);
            $csv   = $formatter->toCsv();

            Storage::disk('local')->put('csvfile.csv',$csv);

        });

        $promise->wait();

    }
  
}