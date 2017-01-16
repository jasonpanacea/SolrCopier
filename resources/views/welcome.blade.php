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

<script src="/js/jquery.min.js"></script>
<script src="/js/amazeui.min.js"></script>
<script src="/js/operate.js"></script>
</body>
@endsection
</html>