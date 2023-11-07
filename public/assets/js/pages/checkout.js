var validator;

$(function() {
    const   creditCardMask = document.querySelector('.credit-card-mask'),
    expiryDateMask = document.querySelector('.expiry-date-mask'),
    CVVMask = document.querySelector('.cvv-code-mask');

    if (creditCardMask) {
        new Cleave(creditCardMask, {
            creditCard: true,
            onCreditCardTypeChanged: function (type) {
                $("#card_type").val(type);
                if (type != '' && type != 'unknown') {
                    document.querySelector('.card-type').innerHTML =
                    '<img src="/assets/img/icons/payments/' + type + '-cc.png" height="28"/>';
                } else {
                    document.querySelector('.card-type').innerHTML = '';
                }
            }
        });
    }

    // Expiry Date Mask
    if (expiryDateMask) {
        new Cleave(expiryDateMask, {
            date: true,
            delimiter: '/',
            datePattern: ['m', 'y']
        });
    }

    // CVV Mask
    if (CVVMask) {
        new Cleave(CVVMask, {
            numeral: true,
            numeralPositiveOnly: true
        });
    }

      // Customer Detail form validator
      validator = FormValidation.formValidation(document.getElementById('custom_detail_form'), {
        fields: {
            full_name: {
                validators: {
                    notEmpty: {
                        message: 'This field is required'
                    },
                },
            },
            address: {
                validators: {
                    notEmpty: {
                        message: 'This field is required'
                    }
                }
            },
            city: {
                validators: {
                    notEmpty: {
                        message: 'This field is required'
                    }
                }
            },
            zipCode: {
                validators: {
                    notEmpty: {
                        message: 'This field is required'
                    }
                }
            },
            country: {
                validators: {
                    notEmpty: {
                        message: 'This field is required'
                    }
                }
            }
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                eleValidClass: '',
                rowSelector: '.form-group'
            }),
            // submitButton: new FormValidation.plugins.SubmitButton(),
            autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
            instance.on('plugins.message.placed', function (e) {
                if (e.element.parentElement.classList.contains('form-group')) {
                    e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                }
            });
        }
    });

    checkIfDetailFormIsFilled();

    $("#full_name, #address, #city, #zipCode, #country").on("change", function(){
        checkDetailFormValidate();
    })


    // submit stripe payment
    $("#checkout_form").on("submit", function(e){
        e.preventDefault();
        
        validator.validate().then(function(status) {
            if (status == 'Valid') {
                // validation
                if(!$("#stripe_number").val() || !$("#stripe_exp").val() || !$("#stripe_cvv").val()){
                    Swal.fire({
                        icon: 'warning',
                        title: '',
                        text: 'Please fill the credit card information.',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    }).then(function(){
                        setTimeout(function(){
                            $("#stripe_number").focus();
                        }, 200);
                    });
                    return;
                }

                if($("#card_type").val() == 'unknown' || $("#card_type").val() == ''){
                    Swal.fire({
                        icon: 'warning',
                        title: '',
                        text: "Please enter the valid card number",
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    })
                    return;
                }

                // use manuall input card
                const card_number = $("#stripe_number").val();
                const cvc = $("#stripe_cvv").val()
                const exp_date =  $("#stripe_exp").val();
                const expiration_month = exp_date.split("/")[0];
                const expiration_year = exp_date.split("/")[1];

                Stripe.setPublishableKey($("#checkout_form").attr('data-stripe-publishable-key'));
                Stripe.createToken({
                    number: card_number,
                    cvc: cvc,
                    exp_month: expiration_month,
                    exp_year: expiration_year
                }, stripeResponseHandler);
               
            }
        });
    })

})

function checkDetailFormValidate(){
    validator.validate().then(function(status) {
        if (status == 'Valid') {
            $(".payment-button").removeClass("d-none");
            $(".payment-fake-button").addClass("d-none");
            return true;
        } else {
            $(".payment-button").addClass("d-none");
            $(".payment-fake-button").removeClass("d-none");
            return false;
        }
    });
}

function checkIfDetailFormIsFilled(){
    if(
        $("#full_name").val() == "" ||
        $("#address").val() == "" ||
        $("#city").val() == "" ||
        $("#zipCode").val() == "" ||
        $("#country").val() == ""
    ){
        $(".payment-button").addClass("d-none");
        $(".payment-fake-button").removeClass("d-none");
    } else {
        $(".payment-button").removeClass("d-none");
        $(".payment-fake-button").addClass("d-none");
        
    }
}

function stripeResponseHandler(status, response) {
    if (response.error) {
        Swal.fire({
            icon: 'warning',
            title: '',
            text: response.error.message,
            customClass: {
              confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
        });
        return false;
    } else {
        /* token contains id, last4, and card type */
        var token = response['id'];

        const card_number = $("#stripe_number").val();
        const exp_date =  $("#stripe_exp").val();
        const expiration_month = exp_date.split("/")[0];
        const expiration_year = exp_date.split("/")[1];

        const _url = "/checkout/upgrade-account";

        let data = {
            full_name: $("#full_name").val(),
            address: $("#address").val(),
            city: $("#city").val(),
            zip_code: $("#zipCode").val(),
            country: $("#country").val(),
            
            name: $("#stripe_name").val(),
            card_number,
            card_type: $("#card_type").val(),
            exp_date,
            expiration_month,
            expiration_year,
            token
        };


        $("#checkout_form .payment-button").attr("disabled", true);
        $("#checkout_form .payment-button .fa-spinner").css("display", "inline-block");

        $.ajax({
            url: _url,
            type: "POST",
            data: data,
            success: function (response) {
                if (response.code == 200) {
                    Swal.fire({
                        icon: 'success',
                        title: '',
                        text: "Your payment is received and your subscription is now active. Enjoy all premium features.",
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    }).then(function(){
                        location.href = "/pages-billing";
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: response.title,
                        text: response.message,
                        customClass: {
                          confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    })
                    $("#checkout_form .payment-button .fa-spinner").css("display", "none"); 
                    $("#checkout_form .payment-button").removeAttr("disabled");
                    return;
                }
            },
            error: function (response) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong. Please try again later!',
                    customClass: {
                      confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                })
                $("#checkout_form .payment-button .fa-spinner").css("display", "none"); 
                $("#checkout_form .payment-button").removeAttr("disabled");
                return;
            },
        });
        return true;
    }
}