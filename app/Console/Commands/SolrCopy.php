<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SolrModel\SolrModel;
class SolrCopy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'solr:copy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        SolrModel::syncData(json_decode('[{"src":"dev-tags"},{"dest":"dev-tags"}]'), '127.0.0.1', '8983', '127.0.0.1', '8983',   '*:*');

    }
}
