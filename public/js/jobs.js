/**
 * Created by incpad on 1/16/17.
 */

$(function() {
    $('#jobList').addClass('am-active').siblings("li").removeClass("am-active");
    //https://datatables.net/examples/basic_init/language
    //https://datatables.net/reference/option/language
    var jobsTable = $('#jobs').DataTable({
        "language": {
            "lengthMenu": "Display _MENU_ records per page",
            "zeroRecords": "Nothing found - sorry",
            "info": "Showing page _PAGE_ of _PAGES_",
            "infoEmpty": "No records available",
            "emptyTable": "No data available in table",
            "search": "Search:",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "paginate": {
                "first":      "First",
                "last":       "Last",
                "next":       "Next",
                "previous":   "Previous"
            },
        },
        order: [[0, "desc" ]],
        ajax : {
            url : "/jobProgress",
            dataSrc : 'data'
        },
        columns : [
            {data : 'id'},
            {data : 'srcIndex'},
            {data : 'destIndex'},
            {
                data : 'progress',
                render : function(data, type , row) {
                    return data.copiedNumber + '/' + data.totalNumber;
                }
            },
            {data : 'elapsed'},
            {
                data : 'action' ,
                render : function(data , type , row) {
                    return '<a class="am-btn am-btn-link action-btn" data-jobid="'+data.jobId+'">'+data.action+'</a>';
                }
            },
            {data : 'status'}
        ],
    });

    /** Start the process to refresh table **/
    var nIntervId = setInterval(function() {
        jobsTable.ajax.reload();
    }, 4000);

    $("#jobs").on("click" , ".action-btn" , function(event) {
        var jobId = $(this).data("jobid");
        $.get("/terminateJob" , {
            jobID : jobId
        },function(data) {
            alert(data.msg);
        }).fail(function(reason) {
            console.log(reason);
            alert("Failed to terminate current job.");
        });
    });
});
