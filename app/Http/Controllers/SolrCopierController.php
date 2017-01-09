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
class SolrCopierController extends Controller{
    
    public function getIndexList(Request $request){
        $ip = $request->input('ip');
        $uri = "http://dev.solr.kapner.fitterweb.com:8001/solr/admin/collections?action=LIST&wt=json";
        $client = new GuzzleHttp\Client(['base_uri' => $uri]);
        $response = $client->request('GET');
        $code = $response->getStatusCode();
        $body = $response->getBody();
        $header = $response->getHeaders();
        $data = json_decode($body);
        Log::info($code);
        $collections = $data->collections;
        Log::info($collections);
        return response()->json(['collections' => $collections]);
    }
}
