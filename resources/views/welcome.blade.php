<?php
  function addTimestampe($url) {
    return $url.'?v='.time();
  }
?>
<!doctype html>
<html class="no-js">
@extends('header')
@section('content')
<body>
<form class="am-form am-form-horizontal">
    <div class="am-form-group">
        <label for="src-ip" class="am-u-sm-2 am-form-label">Source Solr IP</label>
        <span class="am-u-sm-4">
            <input type="text" id="src-ip" placeholder="">
        </span>
        <label for="src-port" class="am-u-sm-1 am-form-label">PORT</label>
        <span class="am-u-sm-4 am-u-end">
            <input type="text" id="src-port" placeholder="">
        </span>
    </div>

    <div class="am-form-group">
        <label for="dest-ip" class="am-u-sm-2 am-form-label">Destination Solr IP</label>
        <div class="am-u-sm-4 am-u-end">
            <input type="text" id="dest-ip" placeholder="">
        </div>
        <label for="dest-port" class="am-u-sm-1 am-form-label">PORT</label>
         <span class="am-u-sm-4 am-u-end">
            <input type="text" id="dest-port" placeholder="">
        </span>
    </div>

    <div class="am-form-group">
        <div class="am-u-sm-2 am-u-sm-centered">
            <button type="button" class="am-btn am-btn-primary am-radius" id="next">NEXT STEP</button>
        </div>
    </div>

</form>


<div class="am-panel am-panel-secondary">
    <div class="am-panel-hd">source index list</div>
    <div class="am-panel-bd">
        <form class="am-form am-form-horizontal">
            <div id="srcCollections-group" class="am-form-group am-g am-g-fixed">
                <!-- srcCollections -->
            </div>
            <div class="am-form-group">
        <span class="am-u-sm-2 am-u-sm-offset-1">
            <button type="button" id="all" class="am-btn am-btn-primary am-active">UNSELECT ALL</button>
        </span>
            </div>
        </form>
    </div>
</div>

<div id="dest-index-list-section" class="am-panel am-panel-success">
    <div class="am-panel-hd">destination index list</div>
    <div class="am-panel-bd">
        <form class="am-form am-form-horizontal">
            <div id="destCollections-group" class="am-form-group am-g am-g-fixed">
                <!-- Two loops here. -->
            </div>

        </form>
    </div>
</div>
<div class="am-g am-form-group">
    <ul class="am-avg-sm-2">
        <li>
            <label for="query" class="am-u-sm-2 am-form-label">Solr Query</label>
            <div class="am-u-sm-4 am-u-end">
                <input type="text" id="query" placeholder="">
            </div>
        </li>
        <li>
            <label for="query" class="am-u-sm-2 am-form-label">Batch Size</label>
            <div class="am-u-sm-4 am-u-end">
                <input type="text" id="batch-size" placeholder="">
            </div>
        </li>
        <li>
            <label for="query" class="am-u-sm-2 am-form-label">Sort By</label>
            <div class="am-u-sm-4 am-u-end">
                <input type="text" id="sort-by" placeholder="">
            </div>
        </li>
    </ul>

</div>
<div class="am-g am-form-group">
    <span class="am-u-sm-2 am-u-end">
        <button type="button" class="am-btn am-btn-danger am-radius" id="copy">SUBMIT SYNC JOB</button>
    </span>
</div>



<script src="/js/jquery.min.js"></script>
<script src="/js/amazeui.min.js"></script>
<script src="{{addTimestampe('/js/operate.js')}}"></script>
<script src="{{addTimestampe('/js/copy.js')}}"></script>
</body>
@endsection

</html>
