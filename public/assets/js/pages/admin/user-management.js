var dt_questions = null;

$(function() {
    dt_questions = $('#users_table').DataTable({
        columnDefs: [
            {
                targets: -1,
                searchable: false,
                orderable: false
            }
        ],
        "fnDrawCallback": function (oSettings) {
            $('#questions_table [data-bs-toggle="tooltip"]').each(function () {
                $(this).tooltip({
                    html: true
                })
            });
        },
        displayLength: 50,
        lengthMenu: [50, 100, 150],
        ordering: true,
        order: [[4, 'desc']],
        searching: true,
        orderCellsTop: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      
        scroller: {
            loadingIndicator: true
        },
    });

    $("#users_table tbody").on("click", ".view-detail", function(){
        const u_id = $(this).attr("data-id");
        console.log(u_id);

        const _url = '/admin/users/get-info/' + u_id;

        $.ajax({
            url: _url,
            type: "GET",
            success: function (response) {
                const user = response.user;
                $("#m_name").val(user.name);
                $("#m_email").val(user.name);
                $("#m_address").val(user.name);
                $("#m_city").val(user.name);
                $("#m_zipcode").val(user.name);
                $("#m_country").val(user.name);

                let badge_html = '<span class="badge rounded-pill bg-label-secondary">Free</span>';
                if(user.subscription_status == 1){
                    if(user.subscription_plan == "Annually") {
                        badge_html = '<span class="badge rounded-pill bg-label-success">Annually</span>';
                    } else {
                        badge_html = '<span class="badge rounded-pill bg-label-info">Monthly</span>';
                    }

                    $("#m_subscribed_at").val(user.subscribed_at);
                } else {
                    $("#m_subscribed_at").val("");
                }

                $("#m_plan").html(badge_html);

                $("#user_modal").modal("show");
            },
            error: function (response) {
            },
        });

        
    })
})

