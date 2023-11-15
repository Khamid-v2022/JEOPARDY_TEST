/**
 * Login
 */

'use strict';
(function () {
    $("#formAuthentication").on("submit", function(e) {
        e.preventDefault();

        const name = $("#name").val();
        const password = $("#password").val();

        $("#submit_btn .fa-spinner").removeClass("d-none");
        $("#submit_btn").attr("disabled", true);

        const _url = '/admin/login';
        const data = {
            name, password
        };

        $.ajax({
            url: _url,
            type: "POST",
            data: data,
            success: function(response) {
                if(response.code == 200) {
                    location.href = "/admin";
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
