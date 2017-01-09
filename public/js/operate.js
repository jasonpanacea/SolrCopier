
$(function () {
    $("#next").click(function () {
        var src = $("#src-ip").val();
        var dest = $("#dest-ip").val();
        //need to verify the ip
        var url = "http://dev.solr.kapner.fitterweb.com:8001/solr/admin/collections?action=LIST&wt=json";
        //need to verify the responese
        window.location.href = '/getIndexList';
    });

});