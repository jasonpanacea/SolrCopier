<?php

namespace App\MysqlModel;

use Illuminate\Database\Eloquent\Model;

class CopyJob extends Model
{
    //
    protected $table = 'jobs';

    protected $fillable = ['taskID', 'srcIndex', 'destIndex',
        'query', 'sortField', 'sortOrder', 'batchSize', 'copiedNumber', 'totalNumber', 'status'];

    public function task(){
        return CopyTask::find($this->taskID);
    }

}
