<?php
/**
 * Created by PhpStorm.
 * User: fanjiaqi
 * Date: 4/30/17
 * Time: 10:02
 */

namespace App\MysqlModel;

use Illuminate\Database\Eloquent\Model;
class CopyTask extends Model
{
    protected $table = 'tasks';

    protected $fillable = ['srcHost','srcPort','destHost','destPort', 'status'];

    public function jobs(){
        return $this->hasMany('App\MysqlModel\CopyJob', 'taskID');
    }
    
    public function updateStatus(){
        $this->status = 'finished';
        foreach ($this->jobs as $job){
            if($job->status != 'finished'){
                $this->status = $job->status;
                break;
            }
        }
        $this->save();
    }
}