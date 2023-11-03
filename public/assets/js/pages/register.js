/**
 * Register
 */

'use strict';
(function () {
    $("#formAuthentication").on("submit", function(e) {
        e.preventDefault();

        const name = $("#username").val();
        const email = $("#email").val();
        const password = $("#password").val();

        $("#submit_btn .fa-spinner").removeClass("d-none");
        $("#submit_btn").attr("disabled", true);

        const _url = 'register';
        const data = {
            name, email, password
        };

        $.ajax({
            url: _url,
            type: "POST",
            data: data,
            success: function(response) {
                if(response.code == 200) {
                    Swal.fire({
                        icon: 'success',
                        title: '',
                        text: 'Successfully added.',
                    }).then(function(){
                        location.href = "/login";
                    })
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                Swal.fire({
                    icon: 'error',
                    title: '',
                    text: XMLHttpRequest.responseJSON.message,
                })
                $("#submit_btn .fa-spinner").addClass("d-none");
                $("#submit_btn").removeAttr("disabled");
            }       
        })
    })
})();
