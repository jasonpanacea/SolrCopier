var copyHandler = {
    advancedSettings : {},
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
            $contentList += '<div class="am-g copy-pair-item" id='+srcItem+' style="display: none;">';
            $contentList += '<label class="am-u-sm-3 am-form-label">'+srcItem+' <span class="am-icon-share"></span></label>';
            $contentList += ' <div class="am-u-sm-3">' + '<select id='+srcItem+'_sel>';
            destCollections && destCollections.forEach(function(destItem , index) {
                $contentList += '<option value='+destItem+'>'+destItem+'</option>';
            });
            $contentList += '</select></div>';
            $contentList += '<a class="am-btn am-btn-link fields-toggle-btn switch-off">Show Src Index Fields</a>';
            $contentList += '<a class="am-btn am-btn-link advanced-settings" data-src-index="'+srcItem+'">Advanced Settings</a>';
            $contentList += '<ul id="'+ srcItem +'-fields-block" class="am-avg-sm-4 am-g-fixed am-u-sm-centered fields-block"></ul>';
            $contentList += '</div>';
        });
        $("#destCollections-group").html("").append($contentList);
    },
    getOmitFields : function($fieldsBlock) {
        var omitFields = [];
        if ($fieldsBlock.is(":visible")) {
            var listItem = $fieldsBlock.children().each(function(index, item) {
                var $input = $(item).find("input");
                if (!$input.prop("checked")) omitFields.push($input.val());
            });
        }
        return omitFields;
    },
    getAdvancedSettings : function(srcIndex) {
        return this.advancedSettings[srcIndex];
    },
    updateAdvancedSettings : function(srcIndex) {
        var $advancedSettingsModel = $("#model-advanced-settings");
        this.advancedSettings[srcIndex] = {
            query : $advancedSettingsModel.find("#query").val(),
            batchSize : $advancedSettingsModel.find("#batch-size").val(),
            sortBy : $advancedSettingsModel.find("#sort-by").val(),
        };
    },
    clearAdvancedSettings : function() {
        var $advancedSettingsModel = $("#model-advanced-settings");
        $advancedSettingsModel.find("input").each(function(ind, ele) {
            $(ele).val("");
        });
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


        $('#srcCollections-group input[type="checkbox"]:checked').each(function(){
            var obj = new Object();
            var $fieldsBlock = $("#"+$(this).val()+"-fields-block");
            var omitFields = [];
            var advancedSettings = copyHandler.getAdvancedSettings($(this).val());

            obj.src = $(this).val();
            obj.dest = $("#"+$(this).val()+"_sel").val();

            omitFields = copyHandler.getOmitFields($fieldsBlock);
            if (omitFields.length) obj.omitFields = omitFields;

            if ($.trim(advancedSettings.batchSize)) {
                obj.batchSize = advancedSettings.batchSize;
            }

            if ($.trim(advancedSettings.query)) {
                obj.query = advancedSettings.query;
            }

            if ($.trim(advancedSettings.sortBy)) {
                obj.sort = advancedSettings.sortBy;
            }

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

    $("#dest-index-list-section").on("click" , ".advanced-settings" , function(event) {
        copyHandler.clearAdvancedSettings();
        $('#model-advanced-settings').modal({
            relatedTarget : $(this),
            onConfirm: function(e) {
                console.log(this.relatedTarget);
                copyHandler.updateAdvancedSettings(this.relatedTarget.attr("data-src-index"));
            },
            onCancel: function(e) {
              copyHandler.clearAdvancedSettings();
            }
        });
    });

    $("#dest-index-list-section").on("click" , ".fields-toggle-btn" ,function(event) {
        var $copyPairItem = $(this).parents(".copy-pair-item");
        var $fieldsBlock = $copyPairItem.find(".fields-block");
        var $selectInput = $copyPairItem.find('select');

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
                        $("<li><label class='fields-item'><input type='checkbox' checked='checked' value='" + currentValue.name +"'/>"+ currentValue.name +"</label></li>")
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
            $fieldsToggleBtn.siblings(".fields-block").html("");
            $fieldsToggleBtn.removeClass("switch-on").addClass("switch-off").text("Show Src Index Fields");
        }
        return $fieldsToggleBtn.hasClass("switch-on");
    }

});
