<?php

namespace App\Jobs;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\SolrModel\SolrModel;
use App\MysqlModel\CopyTask;
use App\MysqlModel\CopyJob;
class SolrIndexCopy implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $copyJob;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CopyJob $job)
    {
        //
        $this->copyJob = $job;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("start handle job: ".$this->copyJob->id);
        $this->copyJob->status = 'scheduled';
        $this->copyJob->save();
        SolrModel::syncData($this->copyJob);
        Log::info("finish handle job: ".$this->copyJob->id);
        $task = $this->copyJob->task;
        $task->updateStatus();
    }

    public function failed(Exception $e)
    {
        Log::error("failed handle job: ".$this->copyJob->id);
        Log::error("fail message: ".$e->getMessage());
        $this->copyJob->status = 'failed';
        $this->copyJob->save();
    }
}
