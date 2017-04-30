<!doctype html>
<html class="no-js">
@extends('header')
@section('content')
    <body>
    <table class="am-table am-table-striped am-table-bordered am-table-compact" id="jobs">
        <thead>
        <tr>
            <th>ID</th>
            <th>indexList</th>
            <th>srcHost</th>
            <th>srcPort</th>
            <th>destHost</th>
            <th>destPort</th>
            <th>query</th>
            <th>created_at</th>
            <th>updated_at</th>
            <th>status</th>
        </tr>
        </thead>

        <tbody>
        @foreach($jobList as $job)
        <tr class="odd gradeX">
            <td>{{$job->id}}</td>
            <td>{{$job->indexList}}</td>
            <td>{{$job->srcHost}}</td>
            <td>{{$job->srcPort}}</td>
            <td>{{$job->destHost}}</td>
            <td>{{$job->destPort}}</td>
            <td>{{$job->query}}</td>
            <td>{{$job->created_at}}</td>
            <td>{{$job->updated_at}}</td>
            <td>{{$job->status}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <script src="/js/amazeui.datatables.min.js"></script>
    <script src ="/js/jobs.js?v=<?php echo time()?>"></script>
    </body>
@endsection

</html>
