<?php

/**
 * Created by PhpStorm.
 * User: jiaqi
 * Date: 7/13/16
 * Time: 2:34 PM
 */
namespace App\SolrModel;
use App\Util\SolrUtils;
use Illuminate\Support\Facades\Log;
use Solarium;
class SolrModel extends SolrBaseModel
{
    public function select($keyWord='Active:true',$fields=['*'])
    {
        $query = $this->client->createSelect();
        $query->setQuery($keyWord);
        $query->setFields($fields);
        $query->setRows(SolrBaseModel::$MAX_ROW_NUMBER);
        // this executes the query and returns the result
        $resultset = $this->client->select($query);
        $numFound = $resultset->getNumFound();
        // show documents using the resultset iterator
        $list = array();
        foreach ($resultset as $document) {
            $item = new \stdClass();
            // the documents are also iterable, to get all fields
            foreach ($document as $field => $value) {
                if (is_array($value)) {
                    $arr = array();
                    foreach ($value as $v){
                        if(json_decode($v))
                            array_push($arr, json_decode($v));
                        else
                            array_push($arr, $v);
                    }
                    $value = $arr;
                }
                $item->$field = $value;
            }
            array_push($list, $item);
        }
        return $list;

    }

    public function selectSort($sortField, $order, $keyWord='Active:true')
    {
        $query = $this->client->createSelect();
        $query->setQuery($keyWord);
        $query->setRows(SolrBaseModel::$MAX_ROW_NUMBER);
        $query->setSorts([$sortField=>$order]);
        // this executes the query and returns the result
        $resultset = $this->client->select($query);
        $numFound = $resultset->getNumFound();
        // show documents using the resultset iterator
        $list = array();
        foreach ($resultset as $document) {
            $item = new \stdClass();
            // the documents are also iterable, to get all fields
            foreach ($document as $field => $value) {
                if (is_array($value)) {
                    $arr = array();
                    foreach ($value as $v){
                        if(json_decode($v))
                            array_push($arr, json_decode($v));
                        else
                            array_push($arr, $v);
                    }
                    $value = $arr;
                }
                $item->$field = $value;
            }
            array_push($list, $item);
        }
        return $list;

    }
    
    public function selectMultiSort($sortfields, $keyWord='Active:true')
    {
    	$query = $this->client->createSelect();
    	$query->setQuery($keyWord);
        $query->setRows(SolrBaseModel::$MAX_ROW_NUMBER);
    	$query->setSorts($sortfields);
    	// this executes the query and returns the result
    	$resultset = $this->client->select($query);
    	$numFound = $resultset->getNumFound();
    	// show documents using the resultset iterator
    	$list = array();
    	foreach ($resultset as $document) {
    		$item = new \stdClass();
    		// the documents are also iterable, to get all fields
    		foreach ($document as $field => $value) {
    			if (is_array($value)) {
    				$arr = array();
    				foreach ($value as $v){
    					if(json_decode($v))
    						array_push($arr, json_decode($v));
    					else
    						array_push($arr, $v);
    				}
    				$value = $arr;
    			}
    			$item->$field = $value;
    		}
    		array_push($list, $item);
    	}
    	return $list;
    
    }

    public function selectByPage($start, $rows, $keyWord='Active:true')
    {
        $query = $this->client->createSelect();
        $query->setQuery($keyWord);
        $query->setStart($start);
        $query->setRows($rows);
        // this executes the query and returns the result
        $resultset = $this->client->select($query);
        $numFound = $resultset->getNumFound();
        // show documents using the resultset iterator
        $list = array();
        foreach ($resultset as $document) {
            $item = new \stdClass();
            // the documents are also iterable, to get all fields
            foreach ($document as $field => $value) {
                $item->$field = $value;
            }
            array_push($list, $item);
        }

        $returnObject = new \stdClass;
        $returnObject->list = $list;
        $returnObject->numFound = $numFound;
        return $returnObject;
    }

    public function selectSortByPage($sortField, $order, $start, $rows, $keyWord='Active:true')
    {
        $query = $this->client->createSelect();
        $query->setQuery($keyWord);
        $query->setStart($start);
        $query->setRows($rows);
        $query->setSorts([$sortField=>$order]);
        // this executes the query and returns the result
        $resultset = $this->client->select($query);
        $numFound = $resultset->getNumFound();
        // show documents using the resultset iterator
        $list = array();
        foreach ($resultset as $document) {
            $item = new \stdClass();
            // the documents are also iterable, to get all fields
            foreach ($document as $field => $value) {
                $item->$field = $value;
            }
            array_push($list, $item);
        }

        $returnObject = new \stdClass;
        $returnObject->list = $list;
        $returnObject->numFound = $numFound;
        return $returnObject;
    }

    public function selectSortByPageWithCursorMark($sortField, $order, $rows, $cursorMark, $keyWord='Active:true')
    {
        $customizer = $this->client->getPlugin('customizerequest');
        $customizer->createCustomization('cursorMark')
            ->setType('param')
            ->setName('cursorMark')
            ->setValue($cursorMark);
        $query = $this->client->createSelect();
        $query->setQuery($keyWord);
        $query->setRows($rows);
        $query->setSorts([$sortField=>$order]);
        // this executes the query and returns the result
        $resultset = $this->client->select($query);
        $numFound = $resultset->getNumFound();
        // show documents using the resultset iterator
        $list = array();
        foreach ($resultset as $document) {
            $item = new \stdClass();
            // the documents are also iterable, to get all fields
            foreach ($document as $field => $value) {
                $item->$field = $value;
            }
            array_push($list, $item);
        }

        $returnObject = new \stdClass;
        $returnObject->list = $list;
        $returnObject->numFound = $numFound;
        $returnObject->nextCursorMark = $resultset->getData()['nextCursorMark'];
        return $returnObject;
    }

    //CAUTION !!!
    //we cannot return the facet query result which is nothing directly 
    //we must use foreach ($facetResult as $key => $value)
    //to get the valid result
    public function facetQuery($facetField, $keyWord, $minCount=1,$limit=1000){
        $query = $this->client->createSelect();
        $query->setQuery($keyWord);
        // get the facetset component
        $facetSet = $query->getFacetSet();
        // create a facet field instance and set options
        $facetSet->createFacetField($facetField)->setField($facetField)->setMinCount($minCount)->setLimit($limit);
        // this executes the query and returns the result
        $resultset = $this->client->select($query);
        $facet = $resultset->getFacetSet()->getFacet($facetField);
        return $facet;
    }

    /**
     * Solr has no 'update' command.
     * But if you add a document with a value for the 'unique key' field
     * that already exists in the index that existing
     * document will be overwritten by your new document.
     * @param $item
     * @return int
     */
    public function update($data, $refreshUpdateDate=true, $commitWithin=false, $omitFields=[]){
        $omitFields = array_merge($omitFields, SolrBaseModel::$OMIT_FIELDS);
        $docList = [];
        if(is_array($data) && isset($data[0])) { //$data is array
            $itemList = $data;
        } else if (!empty($data)) {
            $itemList = [$data];
        } else {
            return;
        }

        // get an update query instance
        $update = $this->client->createUpdate();
        foreach ($itemList as $item){
            if(!is_array($item)) {
                $item = new \ArrayObject($item);
            }

            if (!isset($item["CreatedDate"]) || empty($item["CreatedDate"])) {
                $item["CreatedDate"] = SolrUtils::currentDate();
            }

            if ($refreshUpdateDate) {
                $item["UpdateDate"] = SolrUtils::currentDate();
            } else {
                if(!array_key_exists("UpdateDate", $item)) {
                    $item["UpdateDate"] = SolrUtils::currentDate();
                }
            }

            // create a new document for the data
            $doc = $update->createDocument();
            foreach ($item as $field => $value){
                //upadte data with some fields will cause conflict
                if(in_array($field, $omitFields)) continue;
                if (is_array($value)) {
                    $arr = array();
                    foreach ($value as $v){
                        if(is_array($v))
                            array_push($arr, json_encode($v));
                        else if(is_object($v))
                            array_push($arr,json_encode($v));
                        else
                            array_push($arr, $v);
                    }
                    $value = $arr;
                }
                $doc->$field = $value;
            }
            $docList[] = $doc;
        }
        if($commitWithin){
            // add the documents and commit within certain seconds
            $update->addDocuments($docList, true, SolrBaseModel::$COMMIT_WITHIN_TIME);
        }
        else{
            $update->addDocuments($docList, true);
            $update->addCommit();
        }
        // this executes the query and returns the result
        $result = $this->client->update($update);
        return $result->getStatus();
    }

    //not called by anyone currently
    public function delById($id){
        $update = $this->client->createUpdate();
        // add the delete id and a commit command to the update query
        $update->addDeleteById($id);
        $update->addCommit();

        // this executes the query and returns the result
        $result = $this->client->update($update);

        return $result->getStatus();
    }
    
    public function delByQuery($query="*:*"){
        $update = $this->client->createUpdate();
        // add the delete id and a commit command to the update query
        $update->addDeleteQuery($query);
        $update->addCommit();

        // this executes the query and returns the result
        $result = $this->client->update($update);

        return $result->getStatus();
    }

    //call explicitly to commit data 
    public function commit($soft = false, $waitsearcher = true){
        $update = $this->client->createUpdate();
        $update->addCommit($soft, $waitsearcher);
        $result = $this->client->update($update);
        return $result->getStatus();
    }

    public function spatialQuery($lat, $lon, $dist, $sfield=" LatLong"){
        // get a select query instance and a query helper instance
        $query = $this->client->createSelect();
        $helper = $query->getHelper();
        // using the helper to generate the 'geofilt' filter
        $query->createFilterQuery('region')->setQuery($helper->geofilt($sfield, $lat, $lon, $dist));
        $resultset = $this->client->select($query);
        $numFound = $resultset->getNumFound();
        // show documents using the resultset iterator
        $list = array();
        foreach ($resultset as $document) {
            $item = new \stdClass();
            // the documents are also iterable, to get all fields
            foreach ($document as $field => $value) {
                $item->$field = $value;
            }
            array_push($list, $item);
        }
        $returnObject = new \stdClass;
        $returnObject->list = $list;
        $returnObject->numFound = $numFound;
        return $returnObject;
    }

    public function disMaxQuery($sortField, $order, $start, $rows, $queryalternative, $boostquery, $hightlightFields = []){
        $query = $this->client->createSelect();
        //need to set query to empty string
        $query->setQuery("");
        $query->setStart($start);
        $query->setRows($rows);
        //order by score desc
        if ($sortField == 'score')
            $query->setSorts([$sortField=>$order,'OrderByName'=>'asc']);
        else
            $query->setSorts([$sortField=>$order]);
        // get highlighting component and apply settings
        $hl = $query->getHighlighting();
        $hl->setFields($hightlightFields);
        $hl->setSimplePrefix("");
        $hl->setSimplePostfix("");
        // get the dismax component and set a boost query
        $dismax = $query->getDisMax();
        $dismax->setBoostQuery($boostquery);
        $dismax->setQueryAlternative($queryalternative);

        $resultset = $this->client->select($query);
        $numFound = $resultset->getNumFound();
        $highlighting = $resultset->getHighlighting();

        $list = array();
        foreach ($resultset as $document) {
            $item = new \stdClass();
            foreach ($document as $field => $value) {
                $item->$field = $value;
            }
            // highlighting results can be fetched by document id (the field defined as uniquekey in this schema)
            $highlightedDoc = $highlighting->getResult($document->id);
            if ($highlightedDoc) {
                $highlightItem = new \stdClass();
                foreach ($highlightedDoc as $field => $highlight) {
                    $highlightItem->$field = $highlight;
                }
                $item->highlightItem = $highlightItem;
            }
            array_push($list, $item);
        }

        $returnObject = new \stdClass;
        $returnObject->list = $list;
        $returnObject->numFound = $numFound;
        return $returnObject;
    }
    
    public function getMaxMinScoreofDismaxQuery($queryalternative, $boostquery){
        $query = $this->client->createSelect();
        //need to set query to empty string
        $query->setQuery("");
        $query->setStart(0);
        $query->setRows(2);
        $query->setFields(["score"]);
        $query->setSorts(['score'=>'desc']);
        $dismax = $query->getDisMax();
        $dismax->setBoostQuery($boostquery);
        $dismax->setQueryAlternative($queryalternative);
        $resultset = $this->client->select($query);
        
        //to get max score, order by score desc  
        $list = array();
        foreach ($resultset as $document) {
            $item = new \stdClass();
            foreach ($document as $field => $value) {
                $item->$field = $value;
            }
            array_push($list, $item);
        }
        $max = 0;
        $secondMax = 0;
        if(count($list))
            $max = $list[0]->score;
        if(count($list)>1)
            $secondMax = $list[1]->score;
        
        //to get min score, order by score asc
        $query->setSorts(['score'=>'asc']);
        $resultset = $this->client->select($query);
        $list = array();
        foreach ($resultset as $document) {
            $item = new \stdClass();
            foreach ($document as $field => $value) {
                $item->$field = $value;
            }
            array_push($list, $item);
        }
        $min = 0;
        if(count($list))
            $min = $list[0]->score;
        Log::info("SCORE:MAX,secondMAX,MIN---".json_encode([$max,$secondMax,$min]));
        return [$max,$secondMax,$min];
    }
    
    public function hightlightQuery($sortField, $order, $start, $rows, $hightlightFields, $keyWord){
        // get a select query instance
        $query = $this->client->createSelect();
        $query->setQuery($keyWord);
        $query->setStart($start);
        $query->setRows($rows);
        $query->setSorts([$sortField=>$order]);

        // get highlighting component and apply settings
        $hl = $query->getHighlighting();
        $hl->setFields($hightlightFields);
        $hl->setSimplePrefix("");
        $hl->setSimplePostfix("");
        $resultset = $this->client->select($query);
        $highlighting = $resultset->getHighlighting();
        $numFound = $resultset->getNumFound();
        // show documents using the resultset iterator
        $list = array();
        foreach ($resultset as $document) {
            $item = new \stdClass();
            // the documents are also iterable, to get all fields
            foreach ($document as $field => $value) {
                $item->$field = $value;
            }
            // highlighting results can be fetched by document id (the field defined as uniquekey in this schema)
            $highlightedDoc = $highlighting->getResult($document->id);
            if ($highlightedDoc) {
                $highlightItem = new \stdClass();
                foreach ($highlightedDoc as $field => $highlight) {
                    $highlightItem->$field = $highlight;
                }
                $item->highlightItem = $highlightItem;
            }
            array_push($list, $item);
        }

        $returnObject = new \stdClass;
        $returnObject->list = $list;
        $returnObject->numFound = $numFound;
        return $returnObject;
    }


    public static function syncData($indexList, $srcHost, $srcPort, $destHost, $destPort, $batchSize, $query = "*:*", $deletePreviousData = true)
    {
        Log::info("----------------syncData START--------------------\n");
        foreach ($indexList as $index){
            $fromIndex = new SolrModel($index->src);
            $fromIndex->setConfig($srcHost, $srcPort, $index->src);
            $toIndex = new SolrModel($index->dest);
            $toIndex->setConfig($destHost, $destPort, $index->dest);
            if(isset($index->omitFields))
                $omitFields = $index->omitFields;
            else
                $omitFields = [];
            //delete all previous data or the data the query refers ???
            if($deletePreviousData){
                $toIndex->delByQuery($query);
            }
            $done = false;
            $cursorMark = '*';
            while(!$done){
                $returnObject = $fromIndex->selectSortByPageWithCursorMark('id', 'desc', $batchSize, $cursorMark, $query);
                try {
                    $toIndex->update($returnObject->list, false, true, $omitFields);
                } catch (Exception $e) {
                    Log::info('[Sync Error] index='.$index);
                }
                if($cursorMark == $returnObject->nextCursorMark)
                    $done = true;
                else
                    $cursorMark = $returnObject->nextCursorMark;
            }

            Log::info("Sync from ".$index->src." to ".$index->dest." done.\n");
        }
        Log::info("----------------syncData END--------------------\n");

    }
}
