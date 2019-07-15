<?php

namespace LWS\ExportActions\Listeners;

use LWS\ExportActions\Events\QueueProcessed;
use Illuminate\Support\Facades\DB;

class QueueResponse
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderShipped  $event
     * @return void
     */
    public function handle(QueueProcessed $event)
    {
        \Log::info('File:',['file_name'=>$event->file_name,'table'=>$event->table_id]);
        DB::table('job_update')->where('id',$event->table_id)->limit(1)->update(['status' => 'Ready to Download','file_name'=>$event->file_name]);
    }
}
