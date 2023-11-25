$(function() {
    $("#billing_history_table").DataTable({
        columnDefs: [
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        order: [[0, 'desc']]
    })

    $("#cancel_subscription").on("click", function() {
       
        Swal.fire({
            text: 'Would you like to cancel your subscription?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
        }).then(function (result) {
            if (result.value) {
                $("#cancel_subscription").attr("disabled", true);
                $("#cancel_subscription").find(".fa-spinner").removeClass("d-none");
                let _url = "/checkout/cancel-subscription";
                $.ajax({
                    url: _url,
                    type: "POST",
                    success: function (response) {
                        location.reload();
                    },
                    error: function (response) {
                        $("#cancel_subscription").removeAttr("disabled");
                        $("#cancel_subscription").find(".fa-spinner").addClass("d-none");
                        Swal.fire({
                            icon: 'error',
                            title: '',
                            text: 'Something went wrong. Please try again later',
                        }).then(function() {
                            location.reload();
                        })
                    },
                });
            } 
        });
    })

    $("#upgrade_annually").on("click", function() {
        Swal.fire({
            title: "Upgrade to Annual?",
            text: 'Your card on file will be charged for 12 months ($' + $("#annual_price").val() + ') and you will be credited for your current month.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
        }).then(function (result) {
            if (result.value) {
                $("#upgrade_annually").attr("disabled", true);
                $("#upgrade_annually").find(".fa-spinner").removeClass("d-none");

                let _url = "/checkout/upgrade-account-annually";
                $.ajax({
                    url: _url,
                    type: "POST",
                    success: function (response) {
                        location.reload();
                    },
                    error: function (response) {
                        Swal.fire({
                            icon: 'error',
                            title: '',
                            text: 'Something went wrong. Please try again later',
                        })
                        $("#upgrade_annually").removeAttr("disabled");
                        $("#upgrade_annually").find(".fa-spinner").addClass("d-none");
                    },
                });
            } 
        });
    })

    $("#downgrade_monthly").on("click", function() {
        Swal.fire({
            text: 'Would you like to downgrade your subscription plan to Monthly?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
        }).then(function (result) {
            if (result.value) {
                $("#downgrade_monthly").attr("disabled", true);
                $("#downgrade_monthly").find(".fa-spinner").removeClass("d-none");
                let _url = "/checkout/downgrade-account-monthly";
                $.ajax({
                    url: _url,
                    type: "POST",
                    success: function (response) {
                        location.reload();
                    },
                    error: function (response) {
                        Swal.fire({
                            icon: 'error',
                            title: '',
                            text: 'Something went wrong. Please try again later',
                        })
                        $("#downgrade_monthly").removeAttr("disabled");
                        $("#downgrade_monthly").find(".fa-spinner").addClass("d-none");
                    },
                });
            } 
        });
    })
})