var boardPassersClass;
(function($) {
    $(document).ready(function() {
        var labels = Object.keys(boardPassers);
        var data = Object.values(boardPassers);
        console.log(boardPassers);
        var ctx = document.getElementById('myChart');
        boardPassersClass.ChartData(ctx, 'pie', labels, data)
    });

    boardPassersClass = {
        ChartData:function(ctx, type, labels, data) {
            new Chart(ctx, {
                type: type,
                data: {
                labels: labels,
                datasets: [{
                    label: 'Board Passers',
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
                }
                }
            });
        }
    }
})(jQuery);



