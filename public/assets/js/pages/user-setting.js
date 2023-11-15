$(function() {
    $("#formAccountSettings").on("submit", function(e) {
        e.preventDefault();

        const name = $("#username").val();
        const email = $("#email").val();
        const address = $("#address").val();
        const city = $("#city").val();
        const zipcode = $("#zipCode").val();
        const country = $("#country").val();
        const default_question_count = $("#default_question_count").val();

        $("#submit_btn .fa-spinner").removeClass("d-none");
        $("#submit_btn").attr("disabled", true);

        const _url = '/my-profile/update';
        const data = {
            name, email, address, city, zipcode, country, default_question_count
        };

        $.ajax({
            url: _url,
            type: "POST",
            data: data,
            success: function(response) {
                if(response.code == 200) {
                    const toastPlacementExample = document.querySelector('.toast-placement-ex');
                    $(".toast-body").html("Updated");
                    toastPlacement = new bootstrap.Toast(toastPlacementExample);
                    toastPlacement.show();
                }
                $("#submit_btn .fa-spinner").addClass("d-none");
                $("#submit_btn").removeAttr("disabled");
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

    $("#formAccountDeactivation").on("submit", function(e) {
        e.preventDefault();

        Swal.fire({
            text: 'Are you sure you would like to delete your account?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
        }).then(function (result) {
            if (result.value) {
                // cancel subscription
                let _url = "/checkout/downgrade-account";
                $.ajax({
                    url: _url,
                    type: "POST",
                    success: function (response) {
                        _url = "/my-profile/delete";
                        $.ajax({
                            url: _url,
                            type: "DELETE",
                            success: function (response) {
                                location.reload();
                            },
                            error: function (response) {
                                Swal.fire({
                                    icon: 'error',
                                    title: '',
                                    text: 'Something went wrong. Please try again later',
                                })
                            },
                        });
                    },
                    error: function (response) {
                        Swal.fire({
                            icon: 'error',
                            title: '',
                            text: 'Something went wrong. Please try again later',
                        })
                    },
                });
            } 
        });
    })
})