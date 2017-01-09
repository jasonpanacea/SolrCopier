<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title>Hello Amaze UI</title>

    <link rel="stylesheet" href="/css/amazeui.min.css">
</head>
<body>
<form class="am-form am-form-horizontal">
    <div class="am-form-group">
        @foreach ($collections as $index)
        <label class="am-checkbox-inline">
            <input type="checkbox" value={{$index}}> {{$index}}
        </label>
        @endforeach
    </div>
    <div class="am-form-group">
        <div class="am-u-sm-10 am-u-sm-offset-2">
            <button type="button" class="am-btn am-btn-default am-radius" id="next">SUBMIT SYNC JOB</button>
        </div>
    </div>
</form>

<script src="/js/jquery.min.js"></script>
<script src="/js/amazeui.min.js"></script>
<script src="/js/operate.js"></script>
</body>
</html>