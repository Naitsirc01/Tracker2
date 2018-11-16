@extends('layouts.master')

@section('content')
<body>
<style>
    body {
        font: 10px sans-serif;
    }
    .axis path,
    .axis line {
        fill: none;
        stroke: #000;
        shape-rendering: crispEdges;
    }
    .dot {
        stroke: #000;
    }
</style>

<link href={{asset("vendor/dragit/src/dragit.css")}} rel="stylesheet"/>
<script src={{asset("vendor/dragit/lib/d3.v3.js")}}></script>
<script src={{asset("vendor/dragit/src/dragit.js")}}></script>


<div id="viz"></div>
<p style="clear:both"></p>
<div id="slider"></div>
<button onclick="myTest()"id="play-btn" style="height: 25px; width: 25px;" >test</button>
<label><input type="checkbox" name="mode" value="trajectory" onclick="dragit.trajectory.toggleAll('selected');" checked> Show complete trajectory</label>
<script>
    // Original scatterplot from http://bl.ocks.org/mbostock/3887118
    // Data source http://econ.worldbank.org/WBSITE/EXTERNAL/EXTDEC/EXTRESEARCH/0,,contentMDK:20699068~pagePK:64214825~piPK:64214943~theSitePK:469382,00.html
    // Data source http://catalog.ihsn.org/index.php/catalog/1056/data_dictionary
    var margin = {top: 20, right: 50, bottom: 30, left: 40},
        width = 960 - margin.left - margin.right,
        height = 500 - margin.top - margin.bottom;
    var x = d3.scale.linear()
        .range([0, width]);
    var y = d3.scale.linear()
        .range([height, 0]);
    var color = d3.scale.category10();
    var xAxis = d3.svg.axis()
        .scale(x)
        .orient("bottom");
    var yAxis = d3.svg.axis()
        .scale(y)
        .orient("left");
    var svg = d3.select("#viz").append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
    dragit.time = {min: 0, max: 6, step: 1, current: 6, offset: 1960};
    dragit.object.offsetX = margin.left;
    data = [
        {"name": "Ghana",
            "years": [
                {"year": 1950, "value": 0.68},
                {"year": 1955, "value": 0.80},
                {"year": 1960, "value": 1.05},
                {"year": 1965, "value": 1.95},
                {"year": 1970, "value": 3.06},
                {"year": 1975, "value": 3.86},
                {"year": 1980, "value": 4.53},
                {"year": 1985, "value": 5.20},
                {"year": 1990, "value": 5.65},
                {"year": 1995, "value": 5.89},
                {"year": 2000, "value": 6.44},
                {"year": 2005, "value": 7.07},
                {"year": 2010, "value": 7.00}
            ]},
        {"name": "Thailand",
            "years": [
                {"year": 1950, "value": 2.04},
                {"year": 1955, "value": 2.31},
                {"year": 1960, "value": 2.55},
                {"year": 1965, "value": 2.28},
                {"year": 1970, "value": 2.51},
                {"year": 1975, "value": 3.01},
                {"year": 1980, "value": 3.64},
                {"year": 1985, "value": 4.15},
                {"year": 1990, "value": 4.85},
                {"year": 1995, "value": 5.50},
                {"year": 2000, "value": 5.65},
                {"year": 2005, "value": 7.03},
                {"year": 2010, "value": 7.99}
            ]
        }]
    x.domain(d3.extent(data[1].years, function(d) { return d.year; })).nice();
    y.domain([0, d3.max(data, function(d) { return d3.max(d.years, function(e) { return e.value; }) })]).nice();
    svg.append("g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + height + ")")
        .call(xAxis)
        .append("text")
        .attr("class", "label")
        .attr("x", width)
        .attr("y", -6)
        .style("text-anchor", "end")
        .text("Time");
    svg.append("g")
        .attr("class", "y axis")
        .call(yAxis)
        .append("text")
        .attr("class", "label")
        .attr("transform", "rotate(-90)")
        .attr("y", 6)
        .attr("dy", ".71em")
        .style("text-anchor", "end")
        .text("Years of schooling")
    var gPoints = svg.selectAll(".points")
        .data(data)
        .enter()
        .append("g")
        .attr("class", "points")
        .attr("transform", function(d) {
            return "translate("+x(d.years[dragit.time.current].year)+", "+y(d.years[dragit.time.current].value)+")";
        })
        .on("mouseenter", dragit.trajectory.display)
        .on("mouseleave", dragit.trajectory.remove)
        .call(dragit.object.activate)
    gPoints.append("circle")
        .attr("r", 10)
        .attr("cx", 0)
        .attr("cy", 0)
        .style("fill", function(d) { return color(d.name); })
    gPoints.append("text")
        .attr("x", 50)
        .attr("y", 0)
        .attr("dy", ".35em")
        .style("text-anchor", "end")
        .text(function(d) { return d.name; });
    var legend = svg.selectAll(".legend")
        .data(color.domain())
        .enter().append("g")
        .attr("class", "legend")
        .attr("transform", function(d, i) { return "translate(-200," + i * 20 + ")"; });
    legend.append("rect")
        .attr("x", width - 18)
        .attr("width", 18)
        .attr("height", 18)
        .style("fill", color);
    legend.append("text")
        .attr("x", width - 24)
        .attr("y", 9)
        .attr("dy", ".35em")
        .style("text-anchor", "end")
        .text(function(d) { return d; });
    function update(v, t) {
        dragit.time.current = v || dragit.time.current;

        gPoints.transition().duration(100)
            .attr("transform", function(d) {
                return "translate("+x(d.years[dragit.time.current].year)+", "+y(d.years[dragit.time.current].value)+")";
            })
    }
    function init() {
        dragit.init("svg");
        dragit.data = data.map(function(d, i) {
            console.log(d);
            return d.years.map(function(e, i) {
                return [x(e.year)+margin.left, y(e.value)+margin.top];
            })
        });

        dragit.evt.register("update", update);

        dragit.playback.speed =  500;
        dragit.utils.slider("#slider", true)
        dragit.trajectory.toggleAll('selected');
    }
    init();
    function myTest(){
        d3.selectAll("svg > *").remove();
        $('#viz').empty();

        $('#slider').empty();
        setTimeout(function(){

            window.svg = d3.select("#viz").append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
                .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
            dragit.time = {min: 0, max: 6, step: 1, current: 6, offset: 1960};
            dragit.object.offsetX = margin.left;

            window.svg.append("g")
                .attr("class", "x axis")
                .attr("transform", "translate(0," + height + ")")
                .call(xAxis)
                .append("text")
                .attr("class", "label")
                .attr("x", width)
                .attr("y", -6)
                .style("text-anchor", "end")
                .text("Time");
            window.svg.append("g")
                .attr("class", "y axis")
                .call(yAxis)
                .append("text")
                .attr("class", "label")
                .attr("transform", "rotate(-90)")
                .attr("y", 6)
                .attr("dy", ".71em")
                .style("text-anchor", "end")
                .text("Years of schooling")
            window.gPoints = svg.selectAll(".points")
                .data(data)
                .enter()
                .append("g")
                .attr("class", "points")
                .attr("transform", function(d) {
                    return "translate("+x(d.years[dragit.time.current].year)+", "+y(d.years[dragit.time.current].value)+")";
                })
                .on("mouseenter", dragit.trajectory.display)
                .on("mouseleave", dragit.trajectory.remove)
                .call(dragit.object.activate)
            gPoints.append("circle")
                .attr("r", 10)
                .attr("cx", 0)
                .attr("cy", 0)
                .style("fill", function(d) { return color(d.name); })
            gPoints.append("text")
                .attr("x", 50)
                .attr("y", 0)
                .attr("dy", ".35em")
                .style("text-anchor", "end")
                .text(function(d) { return d.name; });
            window.legend = svg.selectAll(".legend")
                .data(color.domain())
                .enter().append("g")
                .attr("class", "legend")
                .attr("transform", function(d, i) { return "translate(-200," + i * 20 + ")"; });
            window.legend.append("rect")
                .attr("x", width - 18)
                .attr("width", 18)
                .attr("height", 18)
                .style("fill", color);
            window.legend.append("text")
                .attr("x", width - 24)
                .attr("y", 9)
                .attr("dy", ".35em")
                .style("text-anchor", "end")
                .text(function(d) { return d; });
            init();
        }, 2000);
    }
</script>

@endsection