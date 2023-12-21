$(function() {

    // Update/reset user image of account page
    let accountUserImage = document.getElementById('uploadedAvatar');
    const fileInput = document.querySelector('.account-file-input'),
    resetFileInput = document.querySelector('.account-image-reset');
    if (accountUserImage) {
        const resetImage = accountUserImage.src;
        fileInput.onchange = () => {
            if (fileInput.files[0]) {
                accountUserImage.src = window.URL.createObjectURL(fileInput.files[0]);
            }
        };
        resetFileInput.onclick = () => {
            fileInput.value = '';
            accountUserImage.src = resetImage;
        };
    }


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

       

        let data = new FormData();
        data.append('name', name);
        data.append('email', email);
        data.append('address', address);
        data.append('city', city);
        data.append('zipcode', zipcode);
        data.append('country', country);
        data.append('default_question_count', default_question_count);

        if($('#upload')[0].files[0]){
            data.append('file', $('#upload')[0].files[0]);
            var imgname  =  $('#upload').val();
            var size  =  $('#upload')[0].files[0].size;

            var ext =  imgname.substr( (imgname.lastIndexOf('.') +1) );
            if(ext != 'jpg' && ext != 'jpeg' && ext != 'png' && ext != 'gif' && ext != 'PNG' && ext != 'JPG' && ext != 'JPEG')
            {
                fileInput.value = '';
                accountUserImage.src = resetImage;
                $("#img-warning").css("color", "red");
                return;
            }
            if(size > 800000) { 
                fileInput.value = '';
                accountUserImage.src = resetImage;
                $("#img-warning").css("color", "red");
                return;
            }
        }

        $("#submit_btn").attr("disabled", true);
        $("#submit_btn .fa-spinner").removeClass("d-none");
        $("#img-warning").css("color", "#a1acb8 !important");

        const _url = '/my-profile/update';

        $.ajax({
            url: _url,
            type: "POST",
            data: data,
            enctype: 'multipart/form-data',
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            success: function(response) {
                if(response.code == 200) {
                    const toastPlacementExample = document.querySelector('.toast-placement-ex');
                    $(".toast-body").html("Updated");
                    toastPlacement = new bootstrap.Toast(toastPlacementExample);
                    toastPlacement.show();
                    location.reload();
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