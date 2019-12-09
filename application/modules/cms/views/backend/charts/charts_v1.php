<div class="row">


        <?php

            $analytics['months'] = array_reverse($analytics['months']);
                $months = array();
                foreach ($analytics['months'] as $value){
                    $months[] = $value;
                }
            $analytics['months'] = $months;

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


<!-- jQuery 2.1.4 -->
<script src="<?=  base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js")?>"></script>
<!-- ChartJS -->
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
        <?php

            if($this->mUserBrowser->isUser("admin") ) {
                $customerJoined = array();
                foreach ($analytics['users_joined'] as $v){
                    $customerJoined[] = $v;
                }
            }

            $storeCreated = array();
            foreach ($analytics['stores_created'] as $v){
                $storeCreated[] = $v;
            }

            $eventCreated = array();
            foreach ($analytics['events_created'] as $v){
                $eventCreated[] = $v;
            }

            $campaignPushed = array();
            foreach ($analytics['campaigns_pushed'] as $v){
                $campaignPushed[] = $v;
            }


        ?>

        // Get context with jQuery - using jQuery's .get() method.
        var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
        // This will get the first returned node in the jQuery collection.
        var areaChart       = new Chart(areaChartCanvas)

        var storeCreated = <?=  json_encode($storeCreated)?>;

        <?php if($this->mUserBrowser->isUser("admin") ){  ?>
            var customerJoined = <?=  json_encode($customerJoined)?>;
        <?php } ?>

        var campaignsPushed = <?=  json_encode($campaignPushed)?>;
        var eventCreated = <?=  json_encode($eventCreated)?>;

        var labelMonths = <?=json_encode($analytics['months'],JSON_OBJECT_AS_ARRAY)?>;

        console.log(labelMonths);


    <?php if($this->mUserBrowser->isUser("admin") ){  ?>
            customerJoined.reverse();
        <?php } ?>
        storeCreated.reverse();
        eventCreated.reverse();
        campaignsPushed.reverse();

       // visits.reverse();
        var areaChartData = {
            labels  : labelMonths,
            datasets: [
                <?php if($this->mUserBrowser->isUser("admin") ){ ?>
                {
                    label               : 'Customer joined',
                    fillColor           : 'rgba(244,178,78,0.9)',
                    strokeColor         : 'rgba(244,178,78,0.9)',
                    pointColor          : 'rgba(244,178,78,0.9)',
                    pointStrokeColor    : 'rgba(244,178,78,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: '#fff',
                    data                : customerJoined
                },
                <?php } ?>
                {
                    label               : 'Stores created',
                    fillColor           : 'rgba(221,75,57,0.9)',
                    strokeColor         : 'rgba(221,75,57,0.9)',
                    pointColor          : 'rgba(221,75,57,0.9)',
                    pointStrokeColor    : 'rgba(221,75,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: '#fff',
                    data                : storeCreated
                },
                {
                    label               : 'Events created',
                    fillColor           : 'rgb(0,166,90)',
                    strokeColor         : 'rgb(0,166,90)',
                    pointColor          : 'rgb(0,166,90)',
                    pointStrokeColor    : 'rgb(0,166,90)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: '#fff',
                    data                : eventCreated
                },
//                {
//                    label               : 'Visits',
//                    fillColor           : '#0073b7',
//                    strokeColor         : '#0073b7',
//                    pointColor          : '#0073b7',
//                    pointStrokeColor    : '#0073b7',
//                    pointHighlightFill  : '#fff',
//                    pointHighlightStroke: '#fff',
//                    data                : visits
//                },
                {
                    label               : 'Campaigns',
                    fillColor           : '#ff7701',
                    strokeColor         : '#ff7701',
                    pointColor          : '#ff7701',
                    pointStrokeColor    : '#ff7701',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: '#fff',
                    data                : campaignsPushed
                }
            ]
        }

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
            responsive              : true,
        }

        //Create the line chart
        areaChart.Line(areaChartData, areaChartOptions)

        //-------------
        //- LINE CHART -
        //--------------
        var lineChartCanvas          = $('#lineChart').get(0).getContext('2d')
        var lineChart                = new Chart(lineChartCanvas)
        var lineChartOptions         = areaChartOptions
        lineChartOptions.datasetFill = false
        lineChart.Line(areaChartData, lineChartOptions)

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
        var pieChart       = new Chart(pieChartCanvas)
        var PieData        = [
            {
                value    : 700,
                color    : '#f56954',
                highlight: '#f56954',
                label    : 'Chrome'
            },
            {
                value    : 500,
                color    : '#00a65a',
                highlight: '#00a65a',
                label    : 'IE'
            },
            {
                value    : 400,
                color    : '#f39c12',
                highlight: '#f39c12',
                label    : 'FireFox'
            },
            {
                value    : 600,
                color    : '#00c0ef',
                highlight: '#00c0ef',
                label    : 'Safari'
            },
            {
                value    : 300,
                color    : '#3c8dbc',
                highlight: '#3c8dbc',
                label    : 'Opera'
            },
            {
                value    : 100,
                color    : '#d2d6de',
                highlight: '#d2d6de',
                label    : 'Navigator'
            }
        ]
        var pieOptions     = {
            //Boolean - Whether we should show a stroke on each segment
            segmentShowStroke    : true,
            //String - The colour of each segment stroke
            segmentStrokeColor   : '#fff',
            //Number - The width of each segment stroke
            segmentStrokeWidth   : 2,
            //Number - The percentage of the chart that we cut out of the middle
            percentageInnerCutout: 50, // This is 0 for Pie charts
            //Number - Amount of animation steps
            animationSteps       : 100,
            //String - Animation easing effect
            animationEasing      : 'easeOutBounce',
            //Boolean - Whether we animate the rotation of the Doughnut
            animateRotate        : true,
            //Boolean - Whether we animate scaling the Doughnut from the centre
            animateScale         : false,
            //Boolean - whether to make the chart responsive to window resizing
            responsive           : true,
            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio  : true,
            //String - A legend template
            legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions)

        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
        var barChart                         = new Chart(barChartCanvas)
        var barChartData                     = areaChartData
        barChartData.datasets[1].fillColor   = '#00a65a'
        barChartData.datasets[1].strokeColor = '#00a65a'
        barChartData.datasets[1].pointColor  = '#00a65a'
        var barChartOptions                  = {
            //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            scaleBeginAtZero        : true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines      : true,
            //String - Colour of the grid lines
            scaleGridLineColor      : 'rgba(0,0,0,.05)',
            //Number - Width of the grid lines
            scaleGridLineWidth      : 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines  : true,
            //Boolean - If there is a stroke on each bar
            barShowStroke           : true,
            //Number - Pixel width of the bar stroke
            barStrokeWidth          : 2,
            //Number - Spacing between each of the X value sets
            barValueSpacing         : 5,
            //Number - Spacing between data sets within X values
            barDatasetSpacing       : 1,
            //String - A legend template
            legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
            //Boolean - whether to make the chart responsive
            responsive              : true,
            maintainAspectRatio     : true
        }

        barChartOptions.datasetFill = false
        barChart.Bar(barChartData, barChartOptions)
    })
</script>

