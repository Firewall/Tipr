google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

function drawChart() {
    function generateData() {
        var arr = [];

        $('#chart-data li').each(function(index, elem) {
            var $elem = $(elem);
            arr.push([$elem.data('day'), $elem.data('amount'), $elem.data('amount')]);
        });

        if (!arr.length < 7) {


            var date = moment(new Date()).subtract('days', arr.length);
            for (var i = arr.length; i < 7; i++) {
                date.subtract('days', 1);
                arr.push([date.format('MMM DD'), 0, 0]);
            }
        }

        arr.push(['Day', 'Donated', { role: 'annotation', format: '0.00' }]);

        arr = arr.reverse();

        return google.visualization.arrayToDataTable(arr);
    }

    var data = generateData();

    var chartDonator;
    if ((chartDonator = document.getElementById('chart-donator')) !== null) {
        new google.visualization.ColumnChart(chartDonator).
            draw(data, {
                width: $('#chart_div').width(),
                height: 200,
                colors: ['#ff6600'],
                legend: { position: 'none' },
                chartArea: {
                    width: '80%'
                },
                vAxis: {
                    viewWindowMode: 'explicit',
                    viewWindow:{ min: 0 }
                }
            });
    }

    var chartRecipient;
    if ((chartRecipient = document.getElementById('chart-recipient')) !== null) {
        new google.visualization.LineChart(chartRecipient).
            draw(data, {
                width: $('#chart_div').width(),
                height: 200,
                colors: ['#ff6600'],
                legend: { position: 'none' },
                chartArea: {
                    width: '80%'
                },
                vAxis: {
                    viewWindowMode: 'explicit',
                    viewWindow:{ min: 0 }
                }
            });
    }
}