<!doctype html>
<html class="no-js">
@extends('header')
@section('content')
<body>
<form class="am-form am-form-horizontal" style="margin-top:2em;">
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
            <button type="button" class="am-btn am-btn-primary am-radius" id="next">UPDATE</button>
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
            <div id="destCollections-group" class="am-form-group am-g">
                <!-- Two loops here. -->
            </div>

        </form>
    </div>
</div>
<div class="am-g am-form-group">
    <span class="am-u-sm-2 am-u-end">
        <button type="button" class="am-btn am-btn-danger am-radius" id="copy">SUBMIT SYNC JOB</button>
    </span>
</div>

<!-- Model -->
<div class="am-modal am-modal-prompt" tabindex="-1" id="model-advanced-settings">
  <div class="am-modal-dialog" style="width:600px;">
    <div class="am-modal-hd">Advanced Settings</div>
    <div class="am-modal-bd">
        <ul class="am-avg-sm-1">
            <li>
                <label for="query" class="am-u-sm-4 am-form-label">Solr Query</label>
                <div class="am-u-sm-4 am-u-end">
                    <input type="text" id="query" placeholder="">
                </div>
            </li>
            <li>
                <label for="query" class="am-u-sm-4 am-form-label">Batch Size</label>
                <div class="am-u-sm-4 am-u-end">
                    <input type="text" id="batch-size" placeholder="">
                </div>
            </li>
            <li>
                <label for="query" class="am-u-sm-4 am-form-label">Sort By</label>
                <div id="sort-by-group" class="am-u-sm-8 am-u-end">
                    <div class="am-g sort-by-item-fixed">
                        <span class="am-fl am-inline-block sort-field" style="margin-right:10px;">id</span>
                        <select class="am-fl sort-order"><option value="asc">asc</option><option value="desc">desc</option></select>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>Cancel</span>
      <span class="am-modal-btn" data-am-modal-confirm>Confirm</span>
    </div>
  </div>
</div>

<script src="/js/operate.js?v=<?php echo time()?>"></script>
<script src="/js/copy.js?v=<?php echo time()?>"></script>
</body>
@endsection

</html>
