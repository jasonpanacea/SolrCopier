<?php
/**
 * Created by PhpStorm.
 * User: jiaqi
 * Date: 1/9/17
 * Time: 4:25 PM
 */
namespace App\Http\Controllers;
use App\MysqlModel\CopyJob;
use App\MysqlModel\CopyTask;
use Illuminate\Http\Request;
use GuzzleHttp;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\Jobs\SolrIndexCopy;
class SolrCopierController extends Controller{

    public function getIndexList(Request $request) {
        $srcIP = $request->input('srcIP');
        $srcPort = $request->input('srcPort');
        $destIP = $request->input('destIP');
        $destPort = $request->input('destPort');
        $srcURL = "http://".$srcIP.":".$srcPort."/solr/admin/collections?action=LIST&wt=json";
        $destURL = "http://".$destIP.":".$destPort."/solr/admin/collections?action=LIST&wt=json";
        $client = new GuzzleHttp\Client(['base_uri' => $srcURL, 'timeout'  => 2.0]);
        try{
            $response = $client->request('GET');
        } catch (RequestException $e){
            return response()->json(['reason'=>'source solr does not exist']);
        }
        $srccode = $response->getStatusCode();

        //get source solr index data
        $body = $response->getBody();
        $data = json_decode($body);
        $srcCollections = $data->collections;

        try {
            $client = new GuzzleHttp\Client(['base_uri' => $destURL, 'timeout'  => 2.0]);
            $response = $client->request('GET');
        } catch (RequestException $e){
            return response()->json(['reason'=>'destination solr does not exist']);

        }
        $destcode = $response->getStatusCode();
        //get source solr index data
        $body = $response->getBody();
        $data = json_decode($body);
        $destCollections = $data->collections;

        return response()->json([
                'srccode'=>$srccode,
                'destcode'=>$destcode ,
                'srcCollections' =>  $srcCollections,
                'destCollections' => $destCollections
            ]);
    }

    public function getFieldList(Request $request){
        $srcIP = $request->input('srcIP');
        $srcPort = $request->input('srcPort');
        $indexName = $request->input('indexName');
        $srcURL = "http://".$srcIP.":".$srcPort."/solr/".$indexName."/schema/fields?wt=json";
        $client = new GuzzleHttp\Client(['base_uri' => $srcURL, 'timeout'  => 2.0]);
        try{
            $response = $client->request('GET');
        } catch (RequestException $e){
            return response()->json(['reason'=>'source solr does not exist']);
        }
        $statusCode = $response->getStatusCode();

        //get source index fields data
        $data = json_decode($response->getBody());
        $fields = $data->fields;
        $filter_fields = [];
        foreach ($fields as $key => $value) {
            if (!(preg_match('/^_[\S]+/' , $value->name))) array_push($filter_fields , $value);
        }
        return response()->json(['statusCode'=>$statusCode, 'fields'=>$filter_fields]);

    }

    public function copyPage(Request $request){
        $srcCollections = $request->cookie('srcCollections');
        $destCollections = $request->cookie('destCollections');
        return view('copy',['srcCollections'=>$srcCollections,'destCollections'=>$destCollections]);
    }


    public function startSyncJob(Request $request){
        $copyTask = new CopyTask();
        $copyTask->status = 'queued';
        $copyTask->srcHost = $request->get('srcHost');
        $copyTask->srcPort = $request->get('srcPort');
        $copyTask->destHost = $request->get('destHost');
        $copyTask->destPort = $request->get('destPort');
        $copyTask->save();
        $indexList = $request->get('indexList');
        foreach ($indexList as $index){
            $copyJob = new CopyJob();
            foreach ($index as $key=>$value)
                is_array($value)?
                    $copyJob->$key = json_encode($value):$copyJob->$key = $value;

            $field_order_array = [];
            foreach (explode(',',$copyJob->sort) as $sort){
                $kv = explode(' ',trim($sort));
                $field_order_array[$kv[0]] = $kv[1];
            }
            if(empty($copyJob->query))
                $copyJob->query = "*:*";
            if(empty($copyJob->batchSize))
                $copyJob->batchSize = 100;
            $copyJob->sort = json_encode($field_order_array);
            $copyJob->status = 'queued';
            $copyJob->taskID = $copyTask->id;
            $copyJob->save();
        }
        $this->dispatch(new SolrIndexCopy($copyTask));
        return response()->json(['id'=>$copyTask->id]);
    }

    public function taskList(Request $request){
        $taskList  = CopyTask::all();
        return view('tasks',['taskList'=>$taskList]);
    }

    public function jobList(Request $request){
        $jobList  = CopyJob::all();
        return view('jobs',['jobList'=>$jobList]);
    }

    public function jobProgress(Request $request) {
        // $jobList  = CopyJob::where('status','scheduled')->get();
        $jobList  = CopyJob::all();
        $filterJobList = array();
        foreach ($jobList as $key => $value) {
            $item = new \stdClass();
            $item->copiedNumber = $value->copiedNumber;
            $item->totalNumber = $value->totalNumber;
            $value->progress = $item;
            array_push($filterJobList , $value);
        }
        return response()->json(['data' => $filterJobList]);
    }
}
