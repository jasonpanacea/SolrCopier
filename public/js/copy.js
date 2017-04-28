
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#copy").click(function () {
        var indexList =[];
        $('input[type="checkbox"]:checked').each(function(){
            var obj = new Object();
            obj.src = $(this).val();
            obj.dest = $("#"+$(this).val()+"_sel").val();
            indexList.push(obj);
        });
        var query = $("#query").val();
        $.post('/startSyncJob',{'indexList':indexList, 'query':query}, function (data) {
            alert('the job has bee submitted');
        });
    });

    $('input[type="checkbox"]').change(function () {
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

        $.post('/getFieldList', {
            // srcIP : ,
            // srcPort : ,
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
