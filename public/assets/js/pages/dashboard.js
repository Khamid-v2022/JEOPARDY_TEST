$(function() {
    $("#recent_history").DataTable({
        columnDefs: [
            {
                targets: 2,
                type: 'time'
            },
            {
                targets: 4,
                searchable: false,
                orderable: false
            }
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        order: [[0, 'desc']]
    })

    $.extend( $.fn.dataTableExt.oSort, {
        "time-pre": function(a) {
            let div = document.createElement('div');
            div.innerHTML = a;
            let sort_val_el = div.getElementsByClassName("sort-value");
            if(sort_val_el.length > 0) {
                return parseInt(sort_val_el[0].innerHTML);
            } else
                return 0;
        },

        "time-asc": function(a, b) {
            return a - b;
        },

        "time-desc": function(a, b) {
            return b - a;
        }
    });

    $(".delete-record").on("click", function() {
        const id = $(this).attr("data-id");
        const parent_row_el = $(this).parents("tr");
        Swal.fire({
            text: 'Are you sure you would like to delete this test record?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
        }).then(function (result) {
            if (result.value) {
                let _url = "/my-tests/delete-test/" + id;
                $.ajax({
                    url: _url,
                    type: "DELETE",
                    success: function (response) {
                        if (response.code == 200) {
                            parent_row_el.remove();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: '',
                                text: 'Something went wrong. Please try again later',
                            })
                        }
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