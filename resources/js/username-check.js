import * as $ from 'jquery';

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let name = $("#name");
    let username = $("#username");
    let message = $("#message");
    let userId = $("#userId").val();
    let url = $("#url").val();

    username.on("change", function(e) {
        if (!e.originalEvent)
            return;

        let usernameText = username.val();
        let nameText = name.val();

        $.ajax(url + "/api/username/check", {
            method: "POST",
            data: { username: usernameText, name: nameText, id: userId },
        success: request => {
            if(request.unique) {
                message.html(usernameText + " is available!");
                message.css({color:"green"});
            }else {
                message.html(usernameText + " is not available, try: " + request.username);
                message.css({color: "red"});
            }

        }
        });
    });

    name.on("change", function() {

        $.ajax(url + "/api/username/generate", {
            method: "POST",
            data: { name: name.val(), id: userId},
        success: request => {
            username.val(request.username);
            message.html(request.username + " is available!");
            message.css({color:"green"});
        }
        });

    });
});
