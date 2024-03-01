var dt_table = null;

$(function() {
    $('#add_update_modal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    })


    dt_table = $('#feature_questions_table').DataTable({
        columnDefs: [
            {
                targets: [-1],
                orderable:false,
                searchable: false,
            },
        ],
        displayLength: 50,
        lengthMenu: [10, 25, 50],
        order: [[0, 'desc']],
        searching: true,
        orderCellsTop: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        scroller: {
            loadingIndicator: true
        },
    });

    $("#feature_questions_table tbody").on("click", ".delete-question", function(){
        const task_id = $(this).attr("data-id");
        Swal.fire({
            text: 'Are you sure you would like to delete this question?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
        }).then(function (result) {
            if (result.value) {
                const _url = '/admin/feature-question-management/delete-featured-question/' + task_id;

                $.ajax({
                    url: _url,
                    type: "DELETE",
                    success: function (response) {
                        $(".delete-question[data-id=" + task_id + "]").parents("tr").remove();

                        const toastPlacementExample = document.querySelector('.toast-placement-ex');
                        $(".toast-body").html("Deleted");
                        toastPlacement = new bootstrap.Toast(toastPlacementExample);
                        toastPlacement.show();
                    },
                    error: function (response) {
                    },
                });
            }
        });

        
    })


    $("#add_question_btn").on("click", function() {
        $("#modal_title").html("Add new Question");
        $("#m_selected_id").val("");
        $("#add_update_modal").modal("show");
    })

    $("#feature_questions_table tbody").on("click", ".edit-question", function(){
        $("#modal_title").html("Update Question");

        let task_id = $(this).parents("tr").attr("data-id");
        let category = $(this).parents("tr").attr("data-category");
        let question = $(this).parents("tr").attr("data-question");
        let answer = $(this).parents("tr").attr("data-answer");
        

        $("#m_selected_id").val(task_id);
        $("#m_category").val(category);
        $("#m_question").val(question);
        $("#m_answer").val(answer);

        $("#add_update_modal").modal("show");
    })

    $("#m_question_form").on("submit", function(e) {
        e.preventDefault();

        let id = $("#m_selected_id").val();
        let category = $("#m_category").val();
        let question = $("#m_question").val();
        let answer = $("#m_answer").val();

        $.ajax({
            url: "/admin/feature-question-management/update-question",
            method: 'post',
            data: {
                id,
                header_id: $("#task_id").val(),
                category, question, answer
            },
            success: function(response){
                if(response.code == 200){
                    const toastPlacementExample = document.querySelector('.toast-placement-ex');
                    $(".toast-body").html(response.message);
                    toastPlacement = new bootstrap.Toast(toastPlacementExample);
                    toastPlacement.show();

                    $("#add_update_modal").modal("hide");

                    if(id) {
                        $("tr[data-id=" + id + "]").attr("data-category", category);
                        $("tr[data-id=" + id + "]").attr("data-question", question);
                        $("tr[data-id=" + id + "]").attr("data-answer", answer);
                        $("tr[data-id=" + id + "]").find("td.question-category").html(category);
                        $("tr[data-id=" + id + "]").find("td.question-question").html(question);
                        $("tr[data-id=" + id + "]").find("td.question-answer").html(answer);
                    } else {
                        location.reload();
                    }
                   
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: '',
                        text: response.message,
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    })
                }
            },
            error: function(response){
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
            }
        });
    })
})
