$(function() {
    $("#recent_history").DataTable({
        columnDefs: [
            {
                targets: 2,
                type: 'time'
            }
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
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

})