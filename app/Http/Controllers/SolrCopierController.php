<?php
/**
 * Created by PhpStorm.
 * User: jiaqi
 * Date: 1/9/17
 * Time: 4:25 PM
 */
namespace App\Http\Controllers;
use App\SolrModel\SolrBaseModel;
use Illuminate\Http\Request;
use GuzzleHttp;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\SolrModel\SolrModel;
class SolrCopierController extends Controller{
    
    public function getIndexList(Request $request){
        $srcIP = $request->input('srcIP');
        $srcPort = $request->input('srcPort');
        $destIP = $request->input('destIP');
        $destPort = $request->input('destPort');
        $srcIP = 'dev.solr.kapner.fitterweb.com';
        $srcPort = '8001';
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
        $collections = $data->collections;

        try {
            $client = new GuzzleHttp\Client(['base_uri' => $destURL, 'timeout'  => 2.0]);
            $response = $client->request('GET');
        } catch (RequestException $e){
            return response()->json(['reason'=>'destination solr does not exist']);

        }
        $destcode = $response->getStatusCode();
        return response()->json(['srccode'=>$srccode,'destcode'=>$destcode])->cookie('collections', $collections, 2);
    }

    public function copyPage(Request $request){
        $collections = $request->cookie('collections');
        return view('copy',['collections'=>$collections]);
    }


    public function startSyncJob(Request $request){
        $indexList = $request->get('indexList');
        $srcHost = $request->get('srcHost');
        $srcPort = $request->get('srcPort');
        $destHost = $request->get('destHost');
        $destPort = $request->get('destPort');
        $query = $request->get('query');
        SolrModel::syncData($indexList, $srcHost, $srcPort, $destHost, $destPort, $query);
    }
}
