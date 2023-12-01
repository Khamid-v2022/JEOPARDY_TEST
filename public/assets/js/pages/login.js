/**
 * Login
 */

'use strict';
(function () {
    $("#formAuthentication").on("submit", function(e) {
        e.preventDefault();

        const email = $("#email").val();
        const password = $("#password").val();

        $("#submit_btn .fa-spinner").removeClass("d-none");
        $("#submit_btn").attr("disabled", true);

        const _url = 'login';
        const data = {
            email, password
        };

        $.ajax({
            url: _url,
            type: "POST",
            data: data,
            success: function(response) {
                if(response.code == 200) {
                    location.href = "/";
                } else if(response.code == 201) {
                    // Need a email verify
                    Swal.fire({
                        icon: 'warning',
                        title: '',
                        text: "Please verify your email",
                    }).then(function(result){
                        $("#verify_email").val(email);
                        $("#emailVerifyForm").submit();
                        // location.href = "/email-verify" + email;
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

    $("#forgotPasswordForm").on("submit", function(e) {
        e.preventDefault();

        const email = $("#email").val();

        $("#submit_btn .fa-spinner").removeClass("d-none");
        $("#submit_btn").attr("disabled", true);

        const _url = '/forgot-password';
        const data = {
            email
        };

        $.ajax({
            url: _url,
            type: "POST",
            data: data,
            success: function(response) {
                if(response.code == 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Please check your email box!',
                        text: 'Email sent successfully',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    }).then(function() {
                        location.href = '/';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong!',
                        text: 'Please try again later',
                    });
                    $("#submit_btn .fa-spinner").addClass("d-none");
                    $("#submit_btn").removeAttr("disabled");
                }
               
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                // Swal.fire({
                //     icon: 'error',
                //     title: '',
                //     text: XMLHttpRequest.responseJSON.message,
                // })
                Swal.fire({
                    icon: 'error',
                    title: 'Something went wrong!',
                    text: 'Please try again later',
                });
                $("#submit_btn .fa-spinner").addClass("d-none");
                $("#submit_btn").removeAttr("disabled");
            }       
        })
    })

    $("#resetPasswordForm").on("submit", function(e) {
        e.preventDefault();
        if($("#password").val() != $("#confirm-password").val()) {
            Swal.fire({
                icon: 'warning',
                title: 'Password do not match!',
                text: 'Please make sure',
            });
            return;
        }

        $("#submit_btn .fa-spinner").removeClass("d-none");
        $("#submit_btn").attr("disabled", true);

        let _url = `/reset-password`;
        let data = {
            email: $("#email").val(),
            password: $("#password").val()
        };

        $.ajax({
            url: _url,
            type: "POST",
            data: data,
            success: function(response) {
                if(response.code == 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated password',
                        text: '',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    }).then(function() {
                        location.href = '/';
                    });
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

    $("#resend_btn").on('click', function(){
        $(this).css("display", "none");
        $(".fa-spinner").removeClass("d-none");
        
        let _url = "/resend-verify-email/" + $("#email").val();
        $.ajax({
            url: _url,
            type: "get",
            success: function (response) {
                if (response.code == 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        buttonsStyling: false
                    });
                    $("#resend_btn").css("display", "inline");
                    $(".fa-spinner").addClass("d-none");
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning!',
                        text: response.message,
                    })
                    $("#resend_btn").css("display", "inline");
                    $(".fa-spinner").addClass("d-none");
                }
            },
            error: function (response) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.responseJSON.message,
                    text: "Something went wrong. Please try again later",
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                })
                $("#resend_btn").css("display", "inline");
                $(".fa-spinner").addClass("d-none");
            },  
        });
    })
})();
