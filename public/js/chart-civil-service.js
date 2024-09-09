var civilService;
(function($) {
    $(document).ready(function() {
        var labels = Object.keys(perCivilService);
        var data = Object.values(perCivilService);
        var ctx = document.getElementById('alumni-per-civil');
        civilService.ChartData(ctx, 'pie', labels, data)
    });

    civilService = {
        ChartData:function(ctx, type, labels, data) {
            new Chart(ctx, {
                type: type,
                data: {
                labels: labels,
                datasets: [{
                    label: 'No. of Alumni',
                    data: data,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                      ],
                      hoverOffset: 4
                }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            display: false
                        }
                    },
                }
            });
        }
    }
})(jQuery);



