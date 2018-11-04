@extends('layouts.master')

@section('content')

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {packages:["motionchart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Fruit');
            data.addColumn('date', 'Date');
            data.addColumn('number', 'Sales');
            data.addColumn('number', 'Expenses');
            data.addColumn('string', 'Location');
            data.addRows([
                ['Apples',  new Date (1988,0,1), 1000, 300, 'East'],
                ['Oranges', new Date (1988,0,1), 1150, 200, 'West'],
                ['Bananas', new Date (1988,0,1), 300,  250, 'West'],
                ['Apples',  new Date (1988,6,1), 1200, 400, 'East'],
                ['Oranges', new Date (1989,6,1), 750,  150, 'West'],
                ['Bananas', new Date (1989,6,1), 788,  617, 'West']
            ]);

            var chart = new google.visualization.MotionChart(document.getElementById('chart_div'));

            chart.draw(data, {width: 600, height:300});
        }
        var sites = {!! $personas !!};
        var dat = new Date( sites[0].fecha_ingreso);
        console.log(dat);
    </script>

    <body>
    <div id="chart_div" style="width: 600px; height: 300px;"></div>
    </body>

@endsection