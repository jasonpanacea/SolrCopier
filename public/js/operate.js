
$(function () {
    $("#next").click(function () {
        var srcIP = $("#src-ip").val();
        var srcPort = $("#src-port").val();
        var destIP = $("#dest-ip").val();
        var destPort = $("#dest-port").val();
        //need to verify the ip&port
        if (!((checkIP(srcIP) && checkIP(destIP)))){
            alert('wrong ip input');
            return;
        }
        if((isNaN(destPort)||isNaN(srcPort))){
            alert('wrong port input');
            return;
        }
        var url = "http://dev.solr.kapner.fitterweb.com:8001/solr/admin/collections?action=LIST&wt=json";
        //need to verify the responese
        window.location.href = '/getIndexList';
        $.post('/getIndexList', {'srcIP':srcIP, 'srcPort':srcPort, 'destIP':destIP, 'destPort':destPort}, function (data) {
            
        });
    });

});


function checkIP(value){
    var exp=/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/;
    var reg = value.match(exp);
    if(reg==null)
    {
        return false;
    }
    return true;
}

function checkPort(value) {
    return isNaN()
}