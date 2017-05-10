<?php
/**
 * Created by PhpStorm.
 * User: fanjiaqi
 * Date: 4/30/17
 * Time: 10:02
 */

namespace App\MysqlModel;
use Log;
use Illuminate\Database\Eloquent\Model;
class CopyTask extends Model
{
    protected $table = 'tasks';

    protected $fillable = ['user_id','srcHost','srcPort','destHost','destPort', 'status'];

    public function jobs(){
        return $this->hasMany('App\MysqlModel\CopyJob', 'taskID');
    }
    public function user(){
        return $this->belongsTo('App\MysqlModel\User', 'user_id');
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
    
    public function checkConfilct(){
        $currentTasks = CopyTask::where('destHost',$this->destHost)->where('destPort',$this->destPort)
            ->where('status','!=', 'finished')->where('id','!=', $this->id)->get();
        $currentJobIndexs = [];
        foreach ($currentTasks as $currentTask){
            $currentJobIndexs = array_merge($currentJobIndexs,$currentTask->jobs()->whereIn('status',['queued','scheduled'])->pluck('destIndex')->toArray());
        }
        $thisJobIndexs = $this->jobs()->pluck('destIndex')->toArray();
        return count(array_intersect($thisJobIndexs,$currentJobIndexs));
    }
}