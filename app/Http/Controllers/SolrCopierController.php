<?php
/**
 * Created by PhpStorm.
 * User: jiaqi
 * Date: 1/9/17
 * Time: 4:25 PM
 */
namespace App\Http\Controllers;
use App\CopyTask;
use App\SolrModel\SolrBaseModel;
use Illuminate\Http\Request;
use GuzzleHttp;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\SolrModel\SolrModel;
use App\Jobs\SolrIndexCopy;
class SolrCopierController extends Controller{

    // public function getIndexList(Request $request){
    //     $srcIP = $request->input('srcIP');
    //     $srcPort = $request->input('srcPort');
    //     $destIP = $request->input('destIP');
    //     $destPort = $request->input('destPort');
    //     $srcURL = "http://".$srcIP.":".$srcPort."/solr/admin/collections?action=LIST&wt=json";
    //     $destURL = "http://".$destIP.":".$destPort."/solr/admin/collections?action=LIST&wt=json";
    //     $client = new GuzzleHttp\Client(['base_uri' => $srcURL, 'timeout'  => 2.0]);
    //     try{
    //         $response = $client->request('GET');
    //     } catch (RequestException $e){
    //         return response()->json(['reason'=>'source solr does not exist']);
    //     }
    //     $srccode = $response->getStatusCode();
    //
    //     //get source solr index data
    //     $body = $response->getBody();
    //     $data = json_decode($body);
    //     $srcCollections = $data->collections;
    //
    //     try {
    //         $client = new GuzzleHttp\Client(['base_uri' => $destURL, 'timeout'  => 2.0]);
    //         $response = $client->request('GET');
    //     } catch (RequestException $e){
    //         return response()->json(['reason'=>'destination solr does not exist']);
    //
    //     }
    //     $destcode = $response->getStatusCode();
    //     //get source solr index data
    //     $body = $response->getBody();
    //     $data = json_decode($body);
    //     $destCollections = $data->collections;
    //
    //     return response()->json(['srccode'=>$srccode,'destcode'=>$destcode])->cookie('srcCollections', $srcCollections, 2)
    //         ->cookie('destCollections', $destCollections, 20)->cookie('srcHost', $srcIP, 20)
    //         ->cookie('srcPort', $srcPort, 20)->cookie('destHost', $destIP, 20)
    //         ->cookie('destPort', $destPort, 20);
    // }

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
        $copyTask->indexList = json_encode($request->get('indexList'));
        $copyTask->srcHost = $request->get('srcHost');
        $copyTask->srcPort = $request->get('srcPort');
        $copyTask->destHost = $request->get('destHost');
        $copyTask->destPort = $request->get('destPort');
        $copyTask->batchSize = $request->get('batchSize', 100);
        $copyTask->query = $request->get('query', '*:*');
        $copyTask->sort = $request->get('sort');
        $copyTask->save();
        $this->dispatch(new SolrIndexCopy($copyTask));
        return response()->json(['id'=>$copyTask->id]);
    }

    public function jobList(Request $request){
        $jobList  = CopyTask::all();
        return view('jobs',['jobList'=>$jobList]);
    }
}
