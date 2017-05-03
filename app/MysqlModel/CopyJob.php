<?php

namespace App\MysqlModel;

use Illuminate\Database\Eloquent\Model;

class CopyJob extends Model
{
    //
    protected $table = 'jobs';

    protected $fillable = ['taskID', 'srcIndex', 'destIndex',
        'query', 'sort', 'batchSize', 'copiedNumber', 'totalNumber', 'status', 'terminate'];

    public function task(){
        return $this->belongsTo('App\MysqlModel\CopyTask', 'taskID');
    }

}
