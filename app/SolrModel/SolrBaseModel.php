<?php
/**
 * Created by PhpStorm.
 * User: jiaqi
 * Date: 7/13/16
 * Time: 2:35 PM
 */

namespace App\SolrModel;
use Illuminate\Support\Facades\Log;
use Solarium;
use DateTime;
class SolrBaseModel
{
    public $host;
    public $port;

    public $indexName;
    public $client;

    public static $PERSONS = "persons";
    public static $CONTENT = "content";
    public static $EXPERIENCES = "experiences";
    public static $ORGANIZATIONS = "organizations";
    public static $LOCATION = "location";
    public static $TAGS = "tags";
    public static $MEDIA = "media";
    public static $ASSETS = "assets";

    public static $PERSON_ITEM = "person";
    public static $EXPERIENCE_ITEM = "experience";
    public static $ORGANIZATIONS_ITEM = "organization";
    public static $CONTENT_ITEM = "content";
    public static $LOCATION_ITEM = "location";
    public static $TAG_ITEM = "tag";

    public static $DEV_INDEX_MAP = array(
        'persons' => 'dev-kapner-persons-v2',
        'content' => 'dev-content',
        'experiences' => 'dev-experiences',
        'organizations' => 'dev-organizations',
        'location' => 'dev-locations',
        'tags' => 'dev-tags',
        'media' => 'dev-media',
        'assets' => 'dev-assets'
    );
    public static $PRO_INDEX_MAP = array(
        'persons' => 'kapner-persons',
        'content' => 'kapner-content',
        'experiences' => 'kapner-experiences',
        'organizations' => 'kapner-organizations',
        'location' => 'kapner-locations',
        'tags' => 'kapner-tags',
        'media' => 'kapner-media',
        'assets' => 'kapner-assets'
    );
    public static $COPY_INDEX_MAP = array(
        'persons' => 'dev-kapner-persons-v2-copy',
        'content' => 'dev-content-copy',
        'experiences' => 'dev-experiences-copy',
        'organizations' => 'dev-organizations-copy',
        'location' => 'dev-locations-copy',
        'tags' => 'dev-tags-copy',
        'media' => 'dev-media-copy',
    );
    public static $DATE_FORMAT = DateTime::ISO8601;

    public static $PER_PAGE = 10;

    public static $OMIT_FIELDS = ["URLSlug_srch", "PortraitURL_srch", "SourceURL_srch", "Name_lookup", "GivenName_phoneme", "FamilyName_phoneme", "_version_", "score"];
    public static $OBJ_FIELDS = ["Email", "Phone"];

    public static $MAX_ROW_NUMBER = 999999;

    public static $COMMIT_WITHIN_TIME = 10000;
    /**
     * SolrBaseModel constructor.
     * @param $indexName
     */
    public function __construct($indexName)
    {
        $appEnv = env('APP_ENV');
        
        if ($appEnv == 'local') {
          $this->host = 'localhost';
          $this->port = 8983;
        } else if ($appEnv == 'dev') {
          $this->host = 'dev.solr.kapner.fitterweb.com';
          $this->port = 8001;
        } else {
          $this->host = 'internal-kapner-solr-cluster-1812282337.us-east-1.elb.amazonaws.com';
          $this->port = 80;
        }

        if ($appEnv == 'dev') {
          $indexMap = SolrBaseModel::$DEV_INDEX_MAP;
        } else {
          $indexMap = SolrBaseModel::$PRO_INDEX_MAP;
        }

        $this->indexName = $indexMap[$indexName];
        $this->client = new Solarium\Client($this->getConfig());
    }
    
    public function setIndexName($indexName){
        $this->indexName = $indexName;
        $this->setClient();
    }
    
    public function setConfig($host, $port, $indexName){
        $this->host = $host;
        $this->port = $port;
        $this->indexName = $indexName;
        $this->setClient();
    }

    /**
     * @return Solarium\Client
     */
    public function getClient()
    {
        return $this->client;
    }
    
    public function setClient(){
        $this->client = new Solarium\Client($this->getConfig());
    }


    public function getConfig(){
        return array(
            'endpoint' => array(
                'localhost' => array(
                    'host' => $this->host,
                    'port' => $this->port,
                    'path' => '/solr/'.$this->indexName.'/',
                )
            )
        );
    }

    public function check()
    {
        // check solarium version available
        Log::info('Solarium library version: ' . Solarium\Client::VERSION . ' - ');

        // create a client instance
        $client = new Solarium\Client($this->getConfig());

        // create a ping query
        $ping = $client->createPing();

        // execute the ping query
        try {
            $result = $client->ping($ping);
            Log::info('Ping query successful');
            Log::info($result->getData());
        } catch (Solarium\Exception $e) {
            Log::error('Ping query failed');
        }

    }

}
