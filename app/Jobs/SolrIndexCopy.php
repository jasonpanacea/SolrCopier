<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\SolrModel\SolrModel;
use App\CopyTask;
class SolrIndexCopy implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $task;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CopyTask $task)
    {
        //
        $this->task = $task;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        SolrModel::syncData($this->task->indexList, $this->task->srcHost, $this->task->srcPort, $this->task->destHost, $this->task->destPort, $this->task->query);

    }
}
