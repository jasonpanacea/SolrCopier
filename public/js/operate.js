
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#next").click(function () {
        var srcIP = $("#src-ip").val();
        var srcPort = $("#src-port").val();
        var destIP = $("#dest-ip").val();
        var destPort = $("#dest-port").val();
        //need to verify port
        if((isNaN(destPort)||isNaN(srcPort))){
            alert('wrong port input');
            return;
        }
        //need to verify the responese
        $.post('/getIndexList', {'srcIP':srcIP, 'srcPort':srcPort, 'destIP':destIP, 'destPort':destPort}, function (data) {
            if(data.srccode == 200 && data.destcode == 200){
                window.location.href = '/copyPage';
            }
            else{
                alert(data.reason);
            }
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