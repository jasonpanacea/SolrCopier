<!doctype html>
<html class="no-js">
@extends('header')
@section('content')
    <body>
    <table class="am-table am-table-striped am-table-bordered am-table-compact" id="tasks">
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
        @foreach($taskList as $task)
        <tr class="odd gradeX">
            <td>{{$task->id}}</td>
            <td>{{$task->indexList}}</td>
            <td>{{$task->srcHost}}</td>
            <td>{{$task->srcPort}}</td>
            <td>{{$task->destHost}}</td>
            <td>{{$task->destPort}}</td>
            <td>{{$task->query}}</td>
            <td>{{$task->created_at}}</td>
            <td>{{$task->updated_at}}</td>
            <td>{{$task->status}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <script src="/js/amazeui.datatables.min.js"></script>
    <script src ="/js/tasks.js?v=<?php echo time()?>"></script>
    </body>
@endsection

</html>
