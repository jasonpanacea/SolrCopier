<!doctype html>
<html class="no-js">
@extends('header')
@section('content')
    <body>
    <div class="am-margin-vertical-sm">
        <table class="am-table am-table-striped am-table-bordered am-table-compact" id="tasks">
            <thead>
            <tr>
                <th>ID</th>
                <th>jobs</th>
                <th>srcHost</th>
                <th>srcPort</th>
                <th>destHost</th>
                <th>destPort</th>
                <th>created_at</th>
                <th>updated_at</th>
                <th>status</th>
            </tr>
            </thead>

            <tbody>
            @foreach($taskList as $task)
            <tr class="odd gradeX">
                <td>
                    <a class="am-btn am-btn-link go-edit-btn" data-taskid="{{$task->id}}" href="/?taskID={{$task->id}}">Edit</a>
                </td>
                <td>
                    <a class="am-btn am-btn-link show-jobs-btn" data-taskid="{{$task->id}}" data-srchost="{{$task->srcHost}}" data-desthost="{{$task->destHost}}">Show Jobs</a>
                </td>
                <td>{{$task->srcHost}}</td>
                <td>{{$task->srcPort}}</td>
                <td>{{$task->destHost}}</td>
                <td>{{$task->destPort}}</td>
                <td>{{$task->created_at}}</td>
                <td>{{$task->updated_at}}</td>
                <td>{{$task->status}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Models -->
    <div class="am-popup" id="jobs-popup" style="width:100%;height:100%;left:0;top:0;margin:0;">
      <div class="am-popup-inner">
        <div class="am-popup-hd">
          <h4 class="am-popup-title"></h4>
          <span data-am-modal-close
                class="am-close">&times;</span>
        </div>
        <div class="am-popup-bd">
            <table class="am-table am-table-striped am-table-bordered am-table-compact" id="job-list-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>srcIndex</th>
                    <th>destIndex</th>
                    <th>query</th>
                    <th>omitFields</th>
                    <th>sort</th>
                    <th>progress</th>
                    <th>elapsed time</th>
                    <th>status</th>
                </tr>
                </thead>
            </table>
        </div>
      </div>
    </div>

    <script src="/js/amazeui.datatables.min.js"></script>
    <script src ="/js/tasks.js?v=<?php echo time()?>"></script>
    </body>
@endsection

</html>
