<?php
/**
 * Created by PhpStorm.
 * User: jiaqi
 * Date: 1/9/17
 * Time: 4:25 PM
 */
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use GuzzleHttp;
use Illuminate\Support\Facades\Log;
use App\SolrModel\SolrModel;
class SolrCopierController extends Controller{
    
    public function getIndexList(Request $request){
        $srcIP = $request->input('srcIP');
        $srcPort = $request->input('srcPort');
        $destIP = $request->input('destIP');
        $destPort = $request->input('destPort');
        $uri = "http://dev.solr.kapner.fitterweb.com:8001/solr/admin/collections?action=LIST&wt=json";
        $client = new GuzzleHttp\Client(['base_uri' => $uri]);
        $response = $client->request('GET');
        $code = $response->getStatusCode();
        $body = $response->getBody();
        $data = json_decode($body);
        Log::info($code);
        $collections = $data->collections;
        Log::info($collections);
        return view('copy',['collections' => $collections, 'code'=>$code]);
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
