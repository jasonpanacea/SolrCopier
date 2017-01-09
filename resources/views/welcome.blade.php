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
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
<form class="am-form am-form-horizontal">
    <div class="am-form-group">
        <label for="src-ip" class="am-u-sm-2 am-form-label">Source Solr IP</label>
        <div class="am-u-sm-6 am-u-end">
            <input type="text" id="src-ip" placeholder="">
        </div>
    </div>

    <div class="am-form-group">
        <label for="dest-ip" class="am-u-sm-2 am-form-label">Destination Solr IP</label>
        <div class="am-u-sm-6 am-u-end">
            <input type="text" id="dest-ip" placeholder="">
        </div>
    </div>

    <div class="am-form-group">
        <div class="am-u-sm-10 am-u-sm-offset-2">
            <button type="button" class="am-btn am-btn-default am-radius" id="next">NEXT STEP</button>
        </div>
    </div>

</form>

<script src="/js/jquery.min.js"></script>
<script src="/js/amazeui.min.js"></script>
<script src="/js/operate.js"></script>
</body>
</html>