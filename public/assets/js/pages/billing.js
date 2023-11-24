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
                let _url = "/checkout/cancel-subscription";
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
            text: 'Would you like to upgrade your subscription plan to Annually?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
        }).then(function (result) {
            if (result.value) {
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
                let _url = "/checkout/downgrade-account-monthly";
                $.ajax({
                    url: _url,
                    type: "POST",
                    success: function (response) {
                        console.log(response);
                        // location.reload();
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