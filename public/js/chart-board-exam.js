var boardExam;
(function($) {
    $(document).ready(function() {
        var labels = Object.keys(perBoardExam);
        var data = Object.values(perBoardExam);
        var ctx = document.getElementById('alumni-per-exam');
        boardExam.ChartData(ctx, 'bar', labels, data)
    });

    boardExam = {
        ChartData:function(ctx, type, labels, data) {
            new Chart(ctx, {
                type: type,
                data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1

                }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                          display: false
                        }
                    }
                }
            });
        }
    }
})(jQuery);



