<div class="row">


        <?php

            $_months = array_reverse(SimpleChart::getMonths());
                $months = array();
                foreach ($_months as $value){
                    $months[] = $value;
                }


        ?>



    <!-- /.col (LEFT) -->
    <div class="col-md-12">
        <!-- AREA CHART -->
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><b><?=Translate::sprint("Overview","")?></b></h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="areaChart" style="height:250px"></canvas>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->

    </div>
    <!-- /.col -->

</div>

<script src="<?=  base_url("views/skin/backend/plugins/chartjs/Chart.js")?>"></script>
<script>
    $(function () {
        /* ChartJS
         * -------
         * Here we will create a few charts using ChartJS
         */

        //--------------
        //- AREA CHART -
        //--------------


        // Get context with jQuery - using jQuery's .get() method.
        var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
        // This will get the first returned node in the jQuery collection.
        var areaChart       = new Chart(areaChartCanvas)

        var labelMonths = <?=json_encode($months,JSON_OBJECT_AS_ARRAY)?>;

       // visits.reverse();
        var areaChartData = {
            labels  : labelMonths,
            datasets: [
                <?php foreach ($chart_v1_home as $key => $chart_module): ?>
                {
                    label               : '<?= (isset($chart_module['label']) ?  Translate::sprint($chart_module['label']): Translate::sprint($key)) ?>',
                    fillColor           : '<?= (isset($chart_module['color']) ?  $chart_module['color']: '#ff7701') ?>',
                    strokeColor         : '<?= (isset($chart_module['color']) ?  $chart_module['color']: '#ff7701') ?>',
                    pointColor          : '<?= (isset($chart_module['color']) ?  $chart_module['color']: '#ff7701') ?>',
                    pointStrokeColor    : '<?= (isset($chart_module['color']) ?  $chart_module['color']: '#ff7701') ?>',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: '#fff',
                    data                : <?=json_encode(array_reverse($chart_module['months']))?>
                },
                <?php endforeach; ?>
            ]
        };

        var areaChartOptions = {
            //Boolean - If we should show the scale at all
            showScale               : true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines      : false,
            //String - Colour of the grid lines
            scaleGridLineColor      : 'rgba(0,0,0,.05)',
            //Number - Width of the grid lines
            scaleGridLineWidth      : 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines  : true,
            //Boolean - Whether the line is curved between points
            bezierCurve             : true,
            //Number - Tension of the bezier curve between points
            bezierCurveTension      : 0.3,
            //Boolean - Whether to show a dot for each point
            pointDot                : true,
            //Number - Radius of each point dot in pixels
            pointDotRadius          : 2,
            //Number - Pixel width of point dot stroke
            pointDotStrokeWidth     : 2,
            //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
            pointHitDetectionRadius : 20,
            //Boolean - Whether to show a stroke for datasets
            datasetStroke           : true,
            //Number - Pixel width of dataset stroke
            datasetStrokeWidth      : 2,
            //Boolean - Whether to fill the dataset with a color
            datasetFill             : false,
            //String - A legend template
            legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
            //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio     : true,
            //Boolean - whether to make the chart responsive to window resizing
            responsive              : true
        }

        //Create the line chart
        areaChart.Line(areaChartData, areaChartOptions);

    })
</script>

