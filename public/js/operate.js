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
    $('#home').addClass('am-active').siblings("li").removeClass("am-active");
    $("#next").click(function () {
        hostConfigHanlder.updateHostInfo();
        var hostInfo = hostConfigHanlder.getHostInfo();
        //need to verify port
        if (!checkIP(hostInfo.srcIP) || !checkIP(hostInfo.destIP)) {
            alert('invalid host address.');
            return;
        }
        if((isNaN(hostInfo.destPort)||isNaN(hostInfo.srcPort))){
            alert('wrong port input');
            return;
        }
        hostConfigHanlder.getIndexList(hostInfo);
    });

});


function checkIP(value){
    if (typeof value == "undefined") {
        return false;
    }
    var exp=/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?/;
    var reg = value.match(exp);
    if(reg==null)
    {
        return false;
    }
    return true;
}

function checkPort(value) {
    if (typeof value == "undefined") {
        return false;
    }
    return isNaN()
}
