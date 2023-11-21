var dt_questions = null;

$(function() {
    $('#import_file_modal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    })

    loadQuestions();

    $("#add_question_btn").on("click", function() {
        $("#m_selected_q_id").val("");

        $("#m_category").val("");
        $("#m_question").val("");
        $("#m_answer").val("");

        $("#modal_title").html("Add New Quesiton");

        $("#question_modal").modal("show");
    });

    $("#question_form").on("submit", function(e) {
        e.preventDefault();

        const _url = '/admin/question-management/create-update-question';
        $.ajax({
            url: _url,
            data: {
                sel_id:  $("#m_selected_q_id").val(),
                category: $("#m_category").val(),
                question: $("#m_question").val(),
                answer: $("#m_answer").val()
            },
            type: "POST",
            success: function (response) {
                $("#question_modal").modal("hide");

                const toastPlacementExample = document.querySelector('.toast-placement-ex');
                $(".toast-body").html("Saved");
                toastPlacement = new bootstrap.Toast(toastPlacementExample);
                toastPlacement.show();

                dt_questions.ajax.reload( null, false );
            },
            error: function (response) {
            },
        });
    })


    $("#form_import_file").on("submit", function(e) {
        e.preventDefault();

        var fileUpload = document.getElementById("formFile");
    
        //Validate whether File is valid Excel file.
        var regex = /^([a-zA-Z0-9\s_\\.\-:()])+(.csv)$/;
        
        if (!regex.test(fileUpload.value.toLowerCase())) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please upload a valid CSV file.',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            })
            return;
        }

        // Get the selected file
        var files = $('#formFile')[0].files;

        if(files.length > 0){
            $("#m_add_file_btn").attr("disabled", true);
            $("#m_add_file_btn .fa-spinner").removeClass("d-none");

            var fd = new FormData();

            // Append data 
            for(let i = 0; i < files.length; i++)
                fd.append('files' + i, files[i]);
            
            $.ajax({
                url: "/admin/question-management/import-question",
                method: 'post',
                data: fd,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response){
                    if(response.code == 200){
                        const toastPlacementExample = document.querySelector('.toast-placement-ex');
                        $(".toast-body").html("Uploaded");
                        toastPlacement = new bootstrap.Toast(toastPlacementExample);
                        toastPlacement.show();

                        // reload table
                        dt_questions.ajax.reload( null, false );

                        $("#form_import_file").trigger('reset');

                        // close the modal
                        $("#import_modal").modal("toggle");
                        $("#m_add_file_btn .fa-spinner").addClass("d-none");
                        $("#m_add_file_btn").removeAttr("disabled");
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
                        $("#m_add_file_btn .fa-spinner").addClass("d-none");
                        $("#m_add_file_btn").removeAttr("disabled");
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
                    $("#m_add_file_btn .fa-spinner").addClass("d-none");
                    $("#m_add_file_btn").removeAttr("disabled");
                }
            });
        } else{
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please select a valid Excel file.',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            })
            return;
        }
    })
})


function loadQuestions(){
    if (dt_questions != undefined && dt_questions != null) 
    {
        dt_questions.destroy();
        dt_questions = null;
    }
    
    dt_questions = $('#questions_table').DataTable({
        columns: [
            // { data: 'id'},
            { data: 'category'},
            { data: 'question'},
            { data: 'answer'},
            { data: 'id'}
        ],
        columnDefs: [
            // {
            //     targets: 0,
            //     width: '50px',
            //     searchable: false,
            //     orderable: false,
            //     render: function(data, type, full, meta) {
            //         return  meta.row + 1;
            //     }
            // },
            {
                targets: 0,
                className: "category"
            },
            {
                targets: 1,
                className: "question"
            },
            {
                targets: 2,
                className: "answer"
            },
            {
                targets: -1,
                searchable: false,
                orderable: false,
                render: function(data, type, full, meta) {
                    let html = '<div class="d-inline-block text-nowrap">';
                       html += '<a href="javascript:;" class="edit-question me-1" data-id="' + data + '"  data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Question"><i class="bx bx-edit"></i></a>';
                       html += '<a href="javascript:;" class="delete-question" data-id="' + data + '" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Question"><i class="bx bx-trash"></i></a>';
                    html += '</div>';
                    return html;
                }
            }
        ],
        "fnDrawCallback": function (oSettings) {
            $('#questions_table [data-bs-toggle="tooltip"]').each(function () {
                $(this).tooltip({
                    html: true
                })
            });
        },
        displayLength: 100,
        lengthMenu: [50, 100, 150],
        serverSide: true,
        ordering: false,
        // order: [[1, 'asc']],
        searching: true,
        orderCellsTop: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: function ( data, callback, settings ) {
            var out = [];
            
            const _url = "/admin/question-management/load-question";
            let post_data = {
                start: data.start,
                length: data.length,
                order_by: data.order,
                search_key: data.search.value
            }

            $.ajax({
                url: _url,
                type: "GET",
                data: post_data,
                success: function (response) {
                    const list = response.list;
                    if(list.length > 0){
                        list.forEach((item) => {
                            out.push(item);
                        })
                    }

                    setTimeout( function () {
                        callback( {
                            draw: data.draw,
                            data: out,
                            recordsTotal: response.total_record,
                            recordsFiltered: response.total_record
                        });
                    }, 50);
                     
                },
                error: function (response) {
                },
            });
        },
        scroller: {
            loadingIndicator: true
        },
    });

    $("#questions_table tbody").on("click", ".edit-question", function(){
        const q_id = $(this).attr("data-id");

        $("#m_selected_q_id").val(q_id);
        $("#m_category").val($(this).parents("tr").find("td.category").html());
        $("#m_question").val($(this).parents("tr").find("td.question").html());
        $("#m_answer").val($(this).parents("tr").find("td.answer").html());

        $("#modal_title").html("Update Quesiton");

        $("#question_modal").modal("show");
    })

    $("#questions_table tbody").on("click", ".delete-question", function(){
        const q_id = $(this).attr("data-id");

        Swal.fire({
            text: 'Are you sure you would like to delete this question?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
        }).then(function (result) {
            if (result.value) {
                // cancel subscription
                let _url = "/admin/question-management/delete-question/" + q_id;
                $.ajax({
                    url: _url,
                    type: "DELETE",
                    success: function (response) {
                        const toastPlacementExample = document.querySelector('.toast-placement-ex');
                        $(".toast-body").html("Deleted");
                        toastPlacement = new bootstrap.Toast(toastPlacementExample);
                        toastPlacement.show();
        
                        dt_questions.ajax.reload( null, false );
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

}