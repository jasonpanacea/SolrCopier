
$(function () {
    $("#copy").click(function () {
        var indexList =[];
        $('input[type="checkbox"]:checked').each(function(){
            indexList.push($(this).val());
        });
        var query = $("#query").val();
        console.log(indexList);
        console.log(query);
        $.post('/submitJob',{'indexList':indexList, 'query':query}, function (data) {

        });
    });

    $("#all").click(function () {
       if ($(this).hasClass("am-active")) {
           $(this).removeClass("am-active");
           $('input[type="checkbox"]').prop('checked', false);
       }
        else{
           $(this).addClass("am-active");
           $('input[type="checkbox"]').prop('checked', true);
       }
    });

});