<!doctype html>
<html class="no-js">
@extends('header')
@section('content')
    <body>
    <div class="am-margin-vertical-sm">
        <table class="am-table am-table-striped am-table-bordered am-table-compact" id="jobs">
            <thead>
            <tr>
                <th>ID</th>
                <th>srcIndex</th>
                <th>destIndex</th>
                <th>progress</th>
                <th>elapsed time</th>
                <th>action</th>
                <th>status</th>
            </tr>
            </thead>
        </table>
    </div>
    <script src="/js/amazeui.datatables.min.js"></script>
    <script src ="/js/jobs.js?v=<?php echo time()?>"></script>
    </body>
@endsection

</html>
