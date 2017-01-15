<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hello Amaze UI</title>

    <link rel="stylesheet" href="/css/amazeui.min.css">
</head>
<body>
<div class="am-panel am-panel-secondary">
    <div class="am-panel-hd">source index list</div>
    <div class="am-panel-bd">
        <form class="am-form am-form-horizontal">
            <div class="am-form-group am-g am-g-fixed">
                @foreach ($srcCollections as $index)
                    @if ($loop->last)
                        <label class="am-u-sm-6 am-u-end">
                            <input type="checkbox" checked="checked" value={{$index}} > {{$index}}
                        </label>

                    @else
                        <label class="am-u-sm-6">
                            <input type="checkbox" checked="checked" value={{$index}} > {{$index}}
                        </label>
                    @endif
                @endforeach
            </div>
            <div class="am-form-group">
        <span class="am-u-sm-2 am-u-sm-offset-1">
            <button type="button" id="all" class="am-btn am-btn-primary am-active">SELECT ALL</button>
        </span>
            </div>
        </form>
    </div>
</div>

<div class="am-panel am-panel-success">
    <div class="am-panel-hd">destination index list</div>
    <div class="am-panel-bd">
        <form class="am-form am-form-horizontal">
            <div class="am-form-group am-g am-g-fixed">
                @foreach ($srcCollections as $srcIndex)
                    <div class="am-g am-g-fixed"  style="display: none" id={{$srcIndex}}>
                        <label class="am-u-sm-4 am-form-label">{{$srcIndex}}</label>
                        <div class="am-u-sm-8 am-u-end">
                            <select>
                                @foreach ($destCollections as $destIndex)
                                    <option value={{$destIndex}}>{{$destIndex}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endforeach
            </div>

        </form>
    </div>
</div>
<div class="am-form-group">
    <label for="query" class="am-u-sm-2 am-form-label">Solr Query</label>
    <div class="am-u-sm-4 am-u-end">
        <input type="text" id="query" placeholder="">
    </div>
</div>
<div class="am-form-group">
            <span class="am-u-sm-2 am-u-sm-offset-1 am-u-end">
            <button type="button" class="am-btn am-btn-danger am-radius" id="copy">SUBMIT SYNC JOB</button>
        </span>
</div>

<script src="/js/jquery.min.js"></script>
<script src="/js/amazeui.min.js"></script>
<script src="/js/js.cookie.js"></script>
<script src="/js/copy.js"></script>
</body>
</html>