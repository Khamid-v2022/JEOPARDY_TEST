$(function() {
    $("#detail_table").DataTable({
        columnDefs: [
            {
                targets: 4,
                type: 'time'
            },
            {
                targets: [1, 2, 3],
                orderable: false
            }
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        order: [[0, 'asc']],
        displayLength: 50,
        lengthMenu: [10, 25, 50]
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

    $(".switch-answer-btn").on("click", function() {
        const detail_id = $(this).attr("data-id");
        let is_correct = 0;

        $(this).addClass("d-none");
        const score = parseInt($("#my_score").html());
        let updated_score = score;
        if($(this).hasClass("incorrect-answer")) {
            is_correct = 1;
            $(this).parents("td").find(".correct-answer").removeClass("d-none");
            updated_score++;
        } else {
            $(this).parents("td").find(".incorrect-answer").removeClass("d-none");
            updated_score--;
        }
        $("#my_score").html(updated_score);

        const _url = '/jeopardy-test/fix-answer'

        $.ajax({
            url: _url,
            type: "POST",
            data: {
                detail_id,
                is_correct
            },
            success: function(response) {
                if(response.code == 200) {
                    const toastPlacementExample = document.querySelector('.toast-placement-ex');
                    $(".toast-body").html("Updated");
                    toastPlacement = new bootstrap.Toast(toastPlacementExample);
                    toastPlacement.show();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                $("#my_score").html(score);
            }       
        })

    })
})