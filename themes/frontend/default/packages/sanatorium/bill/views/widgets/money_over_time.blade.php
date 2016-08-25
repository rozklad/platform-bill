{{-- Chart lib --}}
{{ Asset::queue('nvd3', 'sanatorium/bill::nvd3/nv.d3.min.css', 'style') }}
{{ Asset::queue('d3', 'sanatorium/bill::nvd3/lib/d3.v3.js', 'jquery') }}
{{ Asset::queue('nvd3', 'sanatorium/bill::nvd3/nv.d3.min.js', 'jquery') }}
{{ Asset::queue('utils', 'sanatorium/bill::nvd3/src/utils.js', 'jquery') }}
{{ Asset::queue('tooltip', 'sanatorium/bill::nvd3/src/tooltip.js', 'jquery') }}
{{ Asset::queue('interactiveLayer', 'sanatorium/bill::nvd3/src/interactiveLayer.js', 'jquery') }}
{{ Asset::queue('axis', 'sanatorium/bill::nvd3/src/models/axis.js', 'jquery') }}
{{ Asset::queue('line', 'sanatorium/bill::nvd3/src/models/line.js', 'jquery') }}
{{ Asset::queue('lineWithFocusChart', 'sanatorium/bill::nvd3/src/models/lineWithFocusChart.js', 'jquery') }}


@section('scripts')
    @parent
    <script type="text/javascript">

        // Cache retrieved data
        window.money_graph_data = {};
        window.money_graph_loaded = false;
        window.money_graph_current = null;

        var data = getMoneyData();

        // Create chart
        var chart = nv.models.lineChart()
            .interpolate(true)
                .x(function(d) {
                    return d[0]
                })
                .y(function(d) {
                    return d[1]
                })
                .color([
                    '#198C19',
                    '#FF1919',
                    '#1919FF',
                    '#FFFF19',
                ])
                .transitionDuration(350)
                .showLegend(false)
                .showYAxis(true)
                .showXAxis(true)
                .margin({
                    left: 35,
                    right: 35,
                    bottom: 35,
                    top: 10,
                })
                .useInteractiveGuideline(true);

        // Format of values on X axis
        chart.xAxis
                .tickPadding(20)
                .tickFormat(function(d) {
                    return d3.time.format('%m-%y')(new Date(d))
                });

        // Format of values on Y axis
        chart.yAxis
                .tickFormat(function(d){
                    return d/1000 + 'k';
                });

        function loadGraph() {
            var data = window.money_graph_current;

            // update max and min on Y axis
            chart.forceY([data.min,data.max]);

            d3.select('.nvd3-line svg')
                    .datum(data.lines)
                    .transition()
                    .duration(1500)
                    .call(chart);

            nv.utils.windowResize(chart.update);

            $('#money-over-time').data('chart', chart);

            return chart;

        }

        function getMoneyData(months) {

            $.ajax({
                type: 'GET',
                url: '{{ route('sanatorium.bill.widgets.data') }}',
                data: {months: months}
            }).success(function(response){

                window.money_graph_data[months] = response;
                window.money_graph_current = response;

                if ( window.money_graph_loaded === false ) {
                    nv.addGraph(loadGraph);
                } else {
                    loadGraph();
                }
            });

        }


        $(function(){

            $('[data-months]').click(function(){

                var months = $(this).data('months');

                if ( typeof window.money_graph_data[months] === 'undefined' ) {
                    window.money_graph_data[months] = getMoneyData(months);
                } else {
                    window.money_graph_current = window.money_graph_data[months];
                    loadGraph();
                }
            });

        });


    </script>
@stop


<div class="row">
    <div class="col-xs-8">

    </div>
    <div class="col-xs-4">
        <div class="dropdown pull-right">
            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a href="#" data-months="12">
                        Year
                    </a>
                </li>
                <li>
                    <a href="#" data-months="">
                        All time
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Widget: Visitors and pageviews -->
<div class="row">
    <div class="col-md-12">
        <div class="nvd3-line line-chart text-center"
             id="money-over-time"
             data-y-grid="true"
             data-x-grid="true"
             style="height:50vh">
            <svg></svg>
        </div>
    </div>