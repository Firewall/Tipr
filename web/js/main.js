google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Day', 'Donated'],
        ['Mon', 1.0    ],
        ['Tue', 0.0    ],
        ['Wen', 2.3    ],
        ['Thu', 0.4    ],
        ['Fri', 2.0    ],
        ['Sat', 0.0    ],
        ['Sun', 0.0    ]
    ]);

    var chartDonator;
    if ((chartDonator = document.getElementById('chart-donator')) !== null) {
        new google.visualization.ColumnChart(chartDonator).
            draw(data, {
                width: $('#chart-donator').width(),
                height: 200,
                colors: ['#ff6600'],
                legend: { position: 'none' },
                chartArea: {
                    width: '100%'
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
                legend: { position: 'none' },
                chartArea: {
                    width: '100%'
                }
            });
    }
}