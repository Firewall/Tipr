google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

function drawChart() {
    function generateData() {
        var arr = [];

        $('#chart-data li').each(function(index, elem) {
            var $elem = $(elem);
            arr.push([new Date($elem.data('day')), $elem.data('amount'), $elem.data('amount')]);
        });

        if (!arr.length < 14) {
            var date = new Date(arr[arr.length - 1][0]);
            for (var i = arr.length; i < 14; i++) {
                date.setDate(date.getDate() - 1);
                arr.push([date, 0, 0]);
            }
        }

        arr.push(['Day', 'Donated', { role: 'annotation' }]);

        arr = arr.reverse();

        return google.visualization.arrayToDataTable(arr);
    }

    var data = generateData();

    var chartRecipient;
    if ((chartRecipient = document.getElementById('chart_div')) !== null) {
        new google.visualization.ColumnChart(chartRecipient).
            draw(data, {
                width: $('#chart_div').width(),
                height: 200,
                colors: ['#ff6600'],
                legend: { position: 'none' },
                chartArea: {
                    width: '80%'
                },
                hAxis: {
                    format: 'd'
                }
            });
    }
}