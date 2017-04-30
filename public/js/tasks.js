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
});