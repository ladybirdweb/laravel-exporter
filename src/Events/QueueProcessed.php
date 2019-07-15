<?php

namespace LWS\ExportActions\Events;



class QueueProcessed
{

    public $file_name;
    public $table_id;

    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct($file_name,$table_id)
    {
        $this->file_name = $file_name;
        $this->table_id = $table_id;
    }
}