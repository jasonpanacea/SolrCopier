<!doctype html>
<html class="no-js">
@extends('header')
@section('content')
<body>
<div class="am-panel am-panel-secondary" style="margin:0px;">
    <div class="am-panel-hd">Solr Host Information</div>
    <div class="am-panel-bd">
        <form class="am-form am-form-horizontal" style="margin-top:2em;">
            <ul class="am-avg-sm-2">
                <li class="src-host-info-group am-g">
                    <div class="am-form-group">
                        <div class="am-u-sm-centered am-g am-margin-vertical-sm">
                            <label for="src-ip" class="am-u-sm-4 am-form-label">Source Host</label>
                            <span class="am-u-sm-8">
                                <input type="text" id="src-ip" placeholder="" value="@if($taskInfo){{$taskInfo->srcHost}}@endif">
                            </span>
                        </div>
                        <div class="am-u-sm-centered am-g am-margin-vertical-sm">
                            <label for="src-port" class="am-u-sm-4 am-form-label">Source PORT</label>
                            <span class="am-u-sm-4 am-u-end">
                                <input type="text" id="src-port" placeholder="" value="@if($taskInfo){{$taskInfo->srcPort}}@endif">
                            </span>
                        </div>
                    </div>
                </li>
                <li class="dest-host-info-group am-g">
                    <div class="am-form-group">
                        <div class="am-u-sm-centered am-g am-margin-vertical-sm">
                            <label for="dest-ip" class="am-u-sm-4 am-form-label">Destination Host</label>
                            <div class="am-u-sm-8 am-u-end">
                                <input type="text" id="dest-ip" placeholder="" value="@if($taskInfo){{$taskInfo->destHost}}@endif">
                            </div>
                        </div>
                        <div class="am-u-sm-centered am-g am-margin-vertical-sm">
                            <label for="dest-port" class="am-u-sm-4 am-form-label">Destination PORT</label>
                             <span class="am-u-sm-4 am-u-end">
                                <input type="text" id="dest-port" placeholder="" value="@if($taskInfo){{$taskInfo->destPort}}@endif">
                            </span>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="am-form-group">
                <div class="am-u-sm-offset-1">
                    <button type="button" class="am-btn am-btn-primary am-radius" id="next">GET INDEX LIST</button>
                </div>
            </div>

        </form>
    </div>
</div>



<div id="src-index-list-section" class="am-panel am-panel-secondary" style="margin:0px;display:none;">
    <div class="am-panel-hd">select source indexes to copy</div>
    <div class="am-panel-bd">
        <form class="am-form am-form-horizontal">
            <div id="srcCollections-group" class="am-form-group am-g am-g-fixed">
                <!-- srcCollections -->
            </div>
            <div class="am-form-group">
        <span class="am-u-sm-2 am-u-sm-offset-1">
            <button type="button" id="all" class="am-btn am-btn-primary">SELECT ALL</button>
        </span>
            </div>
        </form>
    </div>
</div>

<div id="dest-index-list-section" class="am-panel am-panel-success" style="display:none;">
    <div class="am-panel-hd">seleted copy pairs</div>
    <div class="am-panel-bd">
        <form class="am-form am-form-horizontal">
            <div id="destCollections-group" class="am-form-group am-g">
                <!-- Two loops here. -->
            </div>

        </form>
    </div>
</div>
<div id="submit-job-section" class="am-g am-form-group" style="display:none;">
    <span class="am-u-sm-offset-1">
        <button type="button" class="am-btn am-btn-danger am-radius" id="copy">SUBMIT SYNC JOB</button>
    </span>
</div>

<!-- Model -->
<div class="am-modal am-modal-prompt" tabindex="-1" id="model-advanced-settings">
  <div class="am-modal-dialog" style="width:600px;">
    <div class="am-modal-hd">Advanced Settings</div>
    <div class="am-modal-bd">
        <ul class="am-avg-sm-1">
            <li class="am-margin-vertical-sm">
                <label for="query" class="am-u-sm-4 am-form-label">Solr Query</label>
                <div class="am-u-sm-4 am-u-end">
                    <textarea type="text" id="query" placeholder=""></textarea>
                </div>
            </li>
            <li class="am-margin-vertical-sm">
                <label for="query" class="am-u-sm-4 am-form-label">Batch Size</label>
                <div class="am-u-sm-4 am-u-end">
                    <input type="text" id="batch-size" placeholder="">
                </div>
            </li>
            <li class="am-margin-vertical-sm">
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

<div class="am-modal am-modal-loading am-modal-no-btn" tabindex="-1" id="modal-loading">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">loading...</div>
    <div class="am-modal-bd">
      <span class="am-icon-spinner am-icon-spin"></span>
    </div>
  </div>
</div>

<script src="/js/operate.js?v=<?php echo time()?>"></script>
<script src="/js/copy.js?v=<?php echo time()?>"></script>
</body>
@endsection

</html>
