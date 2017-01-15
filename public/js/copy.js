
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#copy").click(function () {
        var indexList =[];
        $('input[type="checkbox"]:checked').each(function(){
            indexList.push($(this).val());
        });
        var query = $("#query").val();
        $.post('/startSyncJob',{'indexList':indexList, 'query':query}, function (data) {

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
           $(this).val("SELECT ALL");
       }
        else{
           $(this).addClass("am-active");
           $('input[type="checkbox"]').prop('checked', true);
           $(this).val("UNSELECT ALL");
       }
    });

});