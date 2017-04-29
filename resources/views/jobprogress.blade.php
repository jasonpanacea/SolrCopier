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
    <table class="am-table am-table-striped am-table-bordered am-table-compact" id="jobs">
        <thead>
        <tr>
            <th>ID</th>
            <th>index</th>
            <th>srcHost</th>
            <th>srcPort</th>
            <th>destHost</th>
            <th>destPort</th>
            <th>progress</th>
        </tr>
        </thead>

        <tbody>
        @foreach($jobList as $job)
        <tr class="odd gradeX">
            <td>{{$job->id}}</td>
            <td>src: "dev-story" dest:"dev-copy-story"</td>
            <td>{{$job->srcHost}}</td>
            <td>{{$job->srcPort}}</td>
            <td>{{$job->destHost}}</td>
            <td>{{$job->destPort}}</td>
            <td>566/1200</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/amazeui.min.js"></script>
    <script src="/js/amazeui.datatables.min.js"></script>
    <script src ="{{ addTimestampe('/js/jobs.js') }}"></script>
    </body>
@endsection

</html>
