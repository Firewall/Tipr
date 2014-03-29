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

    var chartDonator;
    if ((chartDonator = document.getElementById('chart-donator')) !== null) {
        new google.visualization.ColumnChart(chartDonator).
            draw(data, {
                width: $('#chart-donator').width(),
                height: 200,
                colors: ['#ff6600'],
                legend: { position: 'none' },
                chartArea: {
                    width: '95%'
                }
            });
    }

    var chartRecipient;
    if ((chartRecipient = document.getElementById('chart-recipient')) !== null) {
        new google.visualization.LineChart(chartRecipient).
            draw(data, {
                width: $('#chart-recipient').width(),
                height: 200,
                colors: ['#ff6600'],
                //legend: { position: 'none' },
                chartArea: {
                    width: '100%'
                }
            });
    }
}