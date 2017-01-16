
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
        console.log(indexList);
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

});