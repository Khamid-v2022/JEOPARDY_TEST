var dt_table = null;

$(function() {
    $('#import_modal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    })

    $('#edit_title_modal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    })
    

    // dt_table = $('#feature_tasks_table').DataTable({
    //     columnDefs: [
    //         {
    //             targets: [1, -1],
    //             orderable:false,
    //             searchable: false,
    //         },
    //     ],
    //     displayLength: 100,
    //     lengthMenu: [50, 100, 150],
    //     // order: [[0, 'asc']],
    //     order:false,
    //     searching: true,
    //     orderCellsTop: true,
    //     dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
    //     scroller: {
    //         loadingIndicator: true
    //     },
    // });

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
            fd.append('title', $('#m_title').val());

            if($('#m_thumbnail')[0].files[0]){
                fd.append('thumbnail', $('#m_thumbnail')[0].files[0]);
            }

            // Append data 
            for(let i = 0; i < files.length; i++){
                fd.append('files' + i, files[i]);
            }
            
            $.ajax({
                url: "/admin/feature-task-management/import-featured-task",
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

                        location.reload()
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

    $("#feature_tasks_table tbody").on("click", ".delete-task", function(){
        const task_id = $(this).attr("data-id");
        Swal.fire({
            text: 'Are you sure you would like to delete this task?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
        }).then(function (result) {
            if (result.value) {
                const _url = '/admin/feature-task-management/delete-featured-task/' + task_id;

                $.ajax({
                    url: _url,
                    type: "DELETE",
                    success: function (response) {
                        $(".delete-task[data-id=" + task_id + "]").parents("tr").remove();
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

    $("#feature_tasks_table tbody").on("click", ".edit-title", function(){
        let title = $(this).parents("tr").attr("data-task_title");
        let task_id = $(this).parents("tr").attr("data-id");

        $("#m_selected_id").val(task_id);
        $("#m_edit_title").val(title);

        $("#edit_title_modal").modal("show");
    })

    $("#m_task_form").on("submit", function(e) {
        e.preventDefault();
        let id = $("#m_selected_id").val();
        let title = $("#m_edit_title").val();

        $.ajax({
            url: "/admin/feature-task-management/update-task-title",
            method: 'post',
            data: {
                id,
                title
            },
            success: function(response){
                if(response.code == 200){
                    const toastPlacementExample = document.querySelector('.toast-placement-ex');
                    $(".toast-body").html("Updated");
                    toastPlacement = new bootstrap.Toast(toastPlacementExample);
                    toastPlacement.show();

                    $("#edit_title_modal").modal("hide");
                    $("tr[data-id=" + id + "]").attr("data-task_title", title);
                    $("tr[data-id=" + id + "]").find("a.task-title").html(title);
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

    $(".ranking-up").on("click", function() {
        let main_id = $(this).parents("tr").attr("data-id");
        let main_rank = $(this).parents("tr").find(".test-rank").html();
        let target_id = $(this).parents("tr").prev("tr").attr("data-id");
        let target_rank = $(this).parents("tr").prev("tr").find(".test-rank").html();

        if(target_id) {
            $(this).parents("tr").prev().before($(this).parents("tr"));
            $("tr[data-id=" + main_id + "]").find(".test-rank").html(target_rank);
            $("tr[data-id=" + target_id + "]").find(".test-rank").html(main_rank);
            switchRanking(main_id, target_id);
        }
    })

    $(".ranking-down").on("click", function() {
        let main_id = $(this).parents("tr").attr("data-id");
        let main_rank = $(this).parents("tr").find(".test-rank").html();
        let target_id = $(this).parents("tr").next("tr").attr("data-id");
        let target_rank = $(this).parents("tr").next("tr").find(".test-rank").html();

        if(target_id) {
            $(this).parents("tr").next().after($(this).parents("tr"));
            $("tr[data-id=" + main_id + "]").find(".test-rank").html(target_rank);
            $("tr[data-id=" + target_id + "]").find(".test-rank").html(main_rank);
            switchRanking(main_id, target_id);
        }
    })

})

function switchRanking(task_id, target_task_id) {
    $(".ranking-up").removeClass("d-none");
    $(".ranking-down").removeClass("d-none");

    // first row hide up button, last row hide down button
    $("table tr:first-child").find(".ranking-up").addClass("d-none");
    $("table tr:last-child").find(".ranking-down").addClass("d-none");
    $.ajax({
        url: "/admin/feature-task-management/update-task-rank",
        method: 'post',
        data: {
            task_id,
            target_task_id
        },
        success: function(response){
        },
        error: function(response){
        }
    });
}
