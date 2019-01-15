//绕过token
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(".post-audit").click(function () {
    target = $(event.target);
    var post_id = target.attr("post-id");
    var status = target.attr("post-action-status");
    $.ajax({
        url:"/Laravel/admin/posts/"+post_id+"/status",
        method: "POST",
        dataType: "json",
        data:{
            'status':status,
        },
        success: function (data) {
            if(data.error != 0){
                alert(data.msg);
                return;
            }
            target.parent().parent().remove();
        }
    });
});