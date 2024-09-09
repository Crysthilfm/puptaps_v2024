var studentSex;
(function($) {
    $(document).ready(function() {
        var labels = Object.keys(studentsPersex);
        var data = Object.values(studentsPersex);
        var ctx = document.getElementById('students-per-sex');
        studentSex.ChartData(ctx, 'pie', labels, data)
    });

    studentSex = {
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



