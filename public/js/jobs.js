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
            "search": "Search:",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "paginate": {
                "first":      "First",
                "last":       "Last",
                "next":       "Next",
                "previous":   "Previous"
            },
        },
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
            {data : 'status'}
        ],
    });

    /** Start the process to refresh table **/
    var nIntervId = setInterval(function() {
        jobsTable.ajax.reload();
    }, 2000);
});
