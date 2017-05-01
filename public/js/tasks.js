/**
 * Created by incpad on 1/16/17.
 */
$(function() {
    $('#taskList').addClass('am-active').siblings("li").removeClass("am-active");

    //https://datatables.net/examples/basic_init/language
    //https://datatables.net/reference/option/language
    $('#tasks').DataTable({
        "language": {
            "lengthMenu": "Display _MENU_ records per page",
            "zeroRecords": "Nothing found - sorry",
            "info": "Showing page _PAGE_ of _PAGES_",
            "infoEmpty": "No records available",
            "search": "Search:",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "paginate": {
                "first":      "First",
                "last":       "Last",
                "next":       "Next",
                "previous":   "Previous"
            },
        }
    });

    $("#tasks").on("click" , ".show-jobs-btn" , function(event) {
        var $btn = $(this);
        event.stopPropagation();
        jobsTable.ajax.url("/getJobListByTaskID?taskID=" + $btn.data("taskid")).load(function(json) {
            console.log($btn .data("srchost"));
            $("#jobs-popup .am-popup-title").text([$btn.data("srchost") , $btn.data("desthost")].join(" => "));
            $("#jobs-popup").modal('open');
        });
        // jobsTable.ajax.reload(function(json) {
        //     console.log($btn .data("srchost"));
        //     $("#jobs-popup .am-popup-title").text([$btn.data("srchost") , $btn.data("desthost")].join(" => "));
        //     $("#jobs-popup").modal('open');
        // });
    });

    var jobsTable = $('#job-list-table').DataTable({
        "language": {
            "lengthMenu": "Display _MENU_ records per page",
            "zeroRecords": "Nothing found - sorry",
            "info": "Showing page _PAGE_ of _PAGES_",
            "infoEmpty": "No records available",
            "search": "Search:",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "paginate": {
                "first":      "First",
                "last":       "Last",
                "next":       "Next",
                "previous":   "Previous"
            },
        },
        autoFill : false,
        ajax : {
            url : "/getJobListByTaskID",
            dataSrc : 'jobs'
        },
        columns : [
            {data : 'id'},
            {data : 'srcIndex'},
            {data : 'destIndex'},
            {data : 'query'},
            {data : 'omitFields'},
            {data : 'sort'},
            {data : 'batchSize'},
            {
                data : 'progress',
                render : function(data, type , row) {
                    return data.copiedNumber + '/' + data.totalNumber;
                }
            },
            {data : 'elapsed'},
            {data : 'status'}
        ],
    });

});
