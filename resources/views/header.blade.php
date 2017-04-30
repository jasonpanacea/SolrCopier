<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Solr Copier</title>

    <link rel="stylesheet" href="/css/amazeui.min.css">
    <link rel="stylesheet" href="/css/amazeui.datatables.min.css"/>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/amazeui.min.js"></script>
    <script src="/js/head.js?v=<?php echo time()?>"></script>
</head>

<header class="am-topbar am-topbar-inverse am-topbar-fixed-top">
    <h1 class="am-topbar-brand">
        <a href="#">Solr Copier</a>
    </h1>

    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#doc-topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

    <div class="am-collapse am-topbar-collapse" id="doc-topbar-collapse">
        <ul class="am-nav am-nav-pills am-topbar-nav">
            <li id="home"><a href="/">Home</a></li>
            <li id="jobList"><a href="/jobList">Job Progress</a></li>
            <li id="taskList"><a href="/taskList">History</a></li>
        </ul>


        <div class="am-topbar-right">
            <button class="am-btn am-btn-primary am-topbar-btn am-btn-sm">Sign Up</button>
        </div>

        <div class="am-topbar-right">
            <button class="am-btn am-btn-primary am-topbar-btn am-btn-sm">Login</button>
        </div>
    </div>
</header>

@yield('content')
