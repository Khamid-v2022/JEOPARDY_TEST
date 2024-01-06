$(function() {
    loadDailyReviewChartInfomation();
})

function loadDailyReviewChartInfomation() {

    const _url = "/get-daily-review";
    $.ajax({
        url: _url,
        type: "GET",
        success: function(response) {
            if(response.code == 200) {
                drawingChart(response);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
        }       
    })

}

function drawingChart(response) {
    
    var chartDom = document.getElementById('daily_review_chart');
    var myChart = echarts.init(chartDom);
    var option;

    // var data = getVirtualData(response);
    // console.log(data);
    const data = [];
    response.histories.forEach((item) => {
        data.push([
            item.date,
            item.test_count
        ]);
    })

    option = {
        tooltip: {
            formatter: '{b0}: {c0}'
        },
        visualMap: {
            min: 0,
            max: 10,
            type: 'piecewise',
            orient: 'horizontal',
            left: 'center',
            top: 10
        },
        calendar: {
            top: 80,
            left: 30,
            right: 30,
            cellSize: ['auto', 13],
            range: [response.from_date, response.to_date],
            itemStyle: {
                borderWidth: 0.5
            },
            yearLabel: { show: false }
        },
        series: {
            type: 'heatmap',
            coordinateSystem: 'calendar',
            data: data
        }
    };
      
    option && myChart.setOption(option);
    window.addEventListener('resize', myChart.resize);
}

// function getVirtualData(response) {
//     const date = +echarts.time.parse(response.from_date);
//     const end = +echarts.time.parse(response.to_date);
//     const dayTime = 3600 * 24 * 1000;
//     const data = [];
//     for (let time = date; time < end; time += dayTime) {
//         data.push([
//             echarts.time.format(time, '{yyyy}-{MM}-{dd}', false),
//             Math.floor(Math.random() * 10000)
//         ]);
//     }
//     return data;
// }