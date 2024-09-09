<!DOCTYPE html>
<html>
<head>
    <title>PDF with Google Chart</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Year', 'Sales'],
                ['2016',  1000],
                ['2017',  1170],
                ['2018',  660],
                ['2019',  1030]
            ]);

            var options = {
                title: 'Company Performance',
                curveType: 'function',
                legend: { position: 'bottom' } 
            };

            var chart = new google.visualization.PieChart(document.getElementById('curve_chart'));

            chart.draw(data, options);

            // Convert the chart to a base64 image
            var imgUri = chart.getImageURI();

            // Set the hidden input value to the image URI
            document.getElementById('chart_base64').value = imgUri;

            // Submit the form to send the base64 image to the server
            document.getElementById('chart-form').submit();
        }
    </script>
</head>
<body>
    <h1>Sales Report</h1>
    <div id="curve_chart" style="width: 900px; height: 500px;"></div>

    <form id="chart-form" action="{{ route('admin.generatePdf') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="chart_base64" id="chart_base64">
    </form>
</body>
</html>