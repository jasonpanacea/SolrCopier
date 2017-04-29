var hostConfigHanlder = {
    hostInfo : {
        srcIP : '',
        srcPort : '',
        destIP : '',
        destPort : ''
    },
    updateHostInfo : function() {
        this.hostInfo = {
            srcIP : $("#src-ip").val(),
            srcPort : $("#src-port").val(),
            destIP : $("#dest-ip").val(),
            destPort : $("#dest-port").val()
        };
    },
    getHostInfo : function() {
        return this.hostInfo;
    },
    getIndexList : function(hostInfo) {
        //need to verify the responese
        $.post('/getIndexList', {'srcIP':hostInfo.srcIP, 'srcPort':hostInfo.srcPort, 'destIP':hostInfo.destIP, 'destPort':hostInfo.destPort}, function (data) {
            if(data.srccode == 200 && data.destcode == 200){
                // window.location.href = '/copyPage';
                console.log(data);
                copyHandler.setSrcIndexList(data.srcCollections);
                copyHandler.setDestIndexList(data.srcCollections , data.destCollections);
            }
            else{
                alert(data.reason);
            }
        });
    },
}

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#next").click(function () {
        hostConfigHanlder.updateHostInfo();
        var hostInfo = hostConfigHanlder.getHostInfo();
        //need to verify port
        if((isNaN(hostInfo.destPort)||isNaN(hostInfo.srcPort))){
            alert('wrong port input');
            return;
        }
        hostConfigHanlder.getIndexList(hostInfo);
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
