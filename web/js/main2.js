google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

function drawChart() {
    function generateData() {
        var arr = [['Day', 'Donated', { role: 'annotation' }]];

        $('#chart-data li').each(function(index, elem) {
            var $elem = $(elem);
            arr.push([$elem.data('day'), $elem.data('amount'), $elem.data('amount')]);
        });

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
                }
            });
    }
}