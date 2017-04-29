var copyHandler = {
    setSrcIndexList : function(srcCollections) {
        var $contentList = '';
        srcCollections && srcCollections.forEach(function(item , index) {
            if (index == (srcCollections.length - 1)) {
                $contentList += '<label class="am-u-sm-6 am-u-end"><input type="checkbox"  value="'+item+'" ></input>'+item+'</label>';
            } else {
                $contentList += '<label class="am-u-sm-6"><input type="checkbox"  value="'+item+'" ></input>'+item+'</label>';
            }
        });
        $("#srcCollections-group").html("").append($($contentList));
    },
    setDestIndexList : function(srcCollections , destCollections) {
        var $contentList = '';
        srcCollections && srcCollections.forEach(function(srcItem , index) {
            $contentList += '<div class="am-g am-g-fixed" id='+srcItem+' style="display: none;">';
            $contentList += '<label class="am-u-sm-4 am-form-label">'+srcItem+' <span class="am-icon-share"></span></label>';
            $contentList += ' <div class="am-u-sm-5 am-u-end">' + '<select id='+srcItem+'_sel>';
            destCollections && destCollections.forEach(function(destItem , index) {
                $contentList += '<option value='+destItem+'>'+destItem+'</option>';
            });
            $contentList += '</select></div>';
            $contentList += '<a class="am-btn am-btn-link fields-toggle-btn switch-off">Show Src Index Fields</a>';
            $contentList += '<ul class="am-avg-sm-4 fields-block"></ul>';
            $contentList += '</div>';
        });
        $("#destCollections-group").html("").append($contentList);
    },
};

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#copy").click(function () {
        var indexList =[];
        var HostInfo = hostConfigHanlder.getHostInfo();
        var query = $("#query").val();
        var batchSize = $("#batch-size").val();
        var sortBy = $("#sort-by").val();

        $('#srcCollections-group input[type="checkbox"]:checked').each(function(){
            var obj = new Object();
            obj.src = $(this).val();
            obj.dest = $("#"+$(this).val()+"_sel").val();
            indexList.push(obj);
            console.log(indexList);
        });

        var postParam = {
            indexList:indexList,
            srcHost : HostInfo.srcIP ,
            srcPort : HostInfo.srcPort,
            destHost : HostInfo.destIP,
            destPort : HostInfo.destPort
        };

        if ($.trim(batchSize)) {
            postParam.batchSize = batchSize;
        }

        if ($.trim(query)) {
            postParam.query = query;
        }

        if ($.trim(sortBy)) {
            postParam.query = query;
        }

        $.post('/startSyncJob',postParam, function (data) {
            alert('the job has bee submitted');
        });
    });

    $("#srcCollections-group").on("change" , 'input[type="checkbox"]' , function () {
        if($(this).prop('checked')){
            $("#"+$(this).val()).show();
        }
        else{
            $("#"+$(this).val()).hide();
        }
    });

    $("#all").click(function () {
       if ($(this).hasClass("am-active")) {
           $(this).removeClass("am-active");
           $('input[type="checkbox"]').prop('checked', false);
           $(this).html("SELECT ALL");
           $('input[type="checkbox"]').each(function () {
               $("#"+$(this).val()).hide();
           });
       }
        else{
           $(this).addClass("am-active");
           $('input[type="checkbox"]').prop('checked', true);
           $(this).html("UNSELECT ALL");
           $('input[type="checkbox"]').each(function () {
               $("#"+$(this).val()).show();
           });
       }
    });

    $("#dest-index-list-section").on("click" , ".fields-toggle-btn" ,function(event) {
        var $fieldsBlock = $(this).next();
        var $selectInput = $(this).prev().children('select');

        if (!toggoleFieldsBtn($(this))) return;

        var HostInfo = hostConfigHanlder.getHostInfo();

        $.post('/getFieldList', {
            srcIP : HostInfo.srcIP,
            srcPort : HostInfo.srcPort,
            indexName : $selectInput.val(),
        }, function(data) {
            if (data.statusCode === 200) {
                var fields = data.fields;
                fields.forEach(function(currentValue) {
                    var $fieldItem = $fieldsBlock.append(
                        $("<li><label class='fields-item'><input type='checkbox' value='" + currentValue.name +"'/>"+ currentValue.name +"</label></li>")
                    );
                });
            } else {
                alert(data);
            }
            // console.log(data);
        });

    });

    function toggoleFieldsBtn($fieldsToggleBtn) {
        if ($fieldsToggleBtn.hasClass("switch-off")) {
            $fieldsToggleBtn.removeClass("switch-off").addClass("switch-on").text("Hide Src Index Fields");
        } else {
            $fieldsToggleBtn.next(".fields-block").html("");
            $fieldsToggleBtn.removeClass("switch-on").addClass("switch-off").text("Show Src Index Fields");
        }
        return $fieldsToggleBtn.hasClass("switch-on");
    }

});
