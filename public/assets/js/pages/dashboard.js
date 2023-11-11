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

    loadChartInfomation();
    

})

function loadChartInfomation() {

    const _url = "/my-tests/get-myscores";
    $.ajax({
        url: _url,
        type: "GET",
        success: function(response) {
            if(response.code == 200) {
                // array_reverse 
                let scores = response.scores.reverse();
                drawingChart(scores);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
        }       
    })

}

function drawingChart(scores) {
    
    var chartDom = document.getElementById('score_chart');
    var myChart = echarts.init(chartDom);
    var option;

    let dates = [];
    let total_scores = [];
    let number_of_questions = [];
    let avg_scores = [];
    
    scores.forEach((item) => {
        dates.push(item.date);
        total_scores.push(item.score);
        number_of_questions.push(item.number_of_questions);
        avg_scores.push((item.score / item.number_of_questions).toFixed(2) * 100);
    })

    option = {
        legend: {
            data: ['AVG Score(%)', 'Total Score', 'Number of Questions']
        },
        tooltip: {
            trigger: 'axis',
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: {
            type: 'category',
            data: dates
        },
        yAxis: {
            type: 'value'
        },
        color: ['#ee6666', '#5470c6', '#91cc75',],
        series: [
            {
                name: 'AVG Score(%)',
                data: avg_scores,
                type: 'line',
                tack: 'Total',
                smooth: true
            },
            {
                name: 'Total Score',
                data: total_scores,
                type: 'line',
                tack: 'Total',
                smooth: true
            },
            {
                name: 'Number of Questions',
                data: number_of_questions,
                type: 'line',
                stack: 'Total',
                smooth: true
            }
        ],
        
    };

    option && myChart.setOption(option);
    window.addEventListener('resize', myChart.resize);
}