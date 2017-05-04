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

    protected $task;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CopyJob $job)
    {
        //
        $this->job = $job;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("start handle job: ".$this->job->id);
        $this->job->status = 'scheduled';
        $this->job->save();
        SolrModel::syncData($this->job);
        Log::info("finish handle job: ".$this->job->id);
        $task = $this->job->task;
        $task->updateStatus();
    }

    public function failed(Exception $e)
    {
        Log::error("failed handle job: ".$this->job->id);
        Log::error("fail message: ".$e->getMessage());
        $this->job->status = 'failed';
        $this->job->save();
    }
}
