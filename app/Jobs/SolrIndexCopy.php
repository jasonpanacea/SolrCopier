<?php

namespace App\Jobs;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
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
        Log::info("start handle task: ".$this->task->id);
        $this->task->status = 'scheduled';
        $this->task->save();
        SolrModel::syncData(json_decode($this->task->indexList), $this->task->srcHost, $this->task->srcPort,
            $this->task->destHost, $this->task->destPort, $this->task->batchSize, $this->task->query);
        Log::info("finish handle task: ".$this->task->id);
        $this->task->status = 'finished';
        $this->task->save();
    }

    public function failed(Exception $e)
    {
        Log::error("failed handle task: ".$this->task->id);
        Log::error("fail message: ".$e->getMessage());
        $this->task->status = 'failed';
        $this->task->save();
    }
}
