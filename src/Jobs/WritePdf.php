<?php

namespace LWS\ExportActions\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use PDF;
use Illuminate\Support\Facades\Storage;
use LWS\ExportActions\Events\QueueProcessed;

class WritePdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $html;
    protected $table_id;
    

    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($html,$table_id)
    {
        $this->html = $html;
        $this->table_id = $table_id;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        PDF::SetTitle('Export Data.');
        PDF::AddPage();
        PDF::writeHTML($this->html);
        $pdfString = PDF::Output('hello_world.pdf','S');
        Storage::disk('local')->put('pdf.pdf',$pdfString);
        event(new QueueProcessed(storage_path()."/app/pdf.pdf",$this->table_id));
    }

   
  
}