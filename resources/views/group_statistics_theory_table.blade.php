<link rel="stylesheet" href="{{ asset('/css/shortcodes.css')}}" type="text/css" />
<link rel="stylesheet" href="{{asset('css/et-line-font/et-line-font.css')}}">
<div class="row margin-left-1">
    @foreach($categories_array as $key => $category)
        <div class="row" class="margin-top3">
            @if(count($topics_array[$key]) == 0)
                <div class="col-md-6">
                    <h3  class="margin-left-1 margin-top3">{{$category}}</h3>
                </div>
                <div class="col-md-6">
                    <h4 class="margin-top4">Ésta categoria aún no cuenta con ningún tema disponible.</h4>
                </div>
            @else
                <div class="row">
                    <h3 class="margin-left-4 margin-top3">{{$category}}</h3>
                </div>
                <div class="row">
                    @foreach($topics_array[$key] as $secondKey => $topic)
                        <div class="col-md-4">
                            <div class="feature-box-103 text-center bmargin">
                                <div id="percentage_{{$category}}_{{$secondKey}}" data-percentage={{$percentages[$key][$secondKey]}} style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="sh-divider-line doubble light  margin"></div>
    @endforeach
</div>
<h3 class="margin-left-3 margin-top2">Estadisticas generales - Teoría.</h3>
<br>
<div id="chartContainerTheory" class="col-centered" style="height: 400px; width: 85%;margin-top:60px;"></div>
<script>
    $(document).ready(function(){
        var categories          = <?php echo json_encode($categories_array); ?>;
        var topics              = <?php echo json_encode($topics_array); ?>;
        var percentages         = <?php echo json_encode($percentages); ?>;
        var people              = <?php echo json_encode($people); ?>;
        var counter = 0;
        var charts = [];
        CanvasJS.addColorSet("greenShades",
            [
                "#2ECC71",
                "#CD6155",
            ]
        );

        var peopleDataPoints        = [];
        var percentagesDataPoints   = [];
        for(var i = 0; i < categories.length; i++){
            for(var j = 0; j < topics[i].length; j++ ){
                var topic = topics[i][j];
                var value = percentages[i][j];
                var firstObject = {'y': people[i][j], 'label': 'Tema', 'indexLabel': topics[i][j]};
                var secondObject = {'y': percentages[i][j] / 100};
                peopleDataPoints.push(firstObject);
                percentagesDataPoints.push(secondObject);
                CanvasJS.addColorSet("greenShades", ["#2ECC71", "#CD6155"]);
                charts[counter] = new CanvasJS.Chart("percentage_"+categories[i]+"_"+j, {
                    theme: "light2",
                    colorSet: "greenShades",
                    animationEnabled: true,
                    subtitles: [{
                        text: topic,
                        fontSize: 16
                    }],
                    data: [{
                        type: "pie",
                        indexLabelFontSize: 10,
                        radius: 100,
                        indexLabel: "{y}",
                        yValueFormatString: "###0.0\"%\"",
                        dataPoints: [
                            { y: value, label: "Porcentaje de usuarios que han visto esto." },
                            { y: 100 - value, label: "Porcentaje de usuarios que no han visto esto."},
                        ]
                    }]
                });
                charts[counter++].render();
            }
        }

        var chart = new CanvasJS.Chart("chartContainerTheory", {
            animationEnabled: true,
            theme: "light2",

            axisY: {
                prefix: "#",
                labelFormatter: addSymbols
            },

            toolTip: {
                shared: true,
            },
            legend: {
                cursor: "pointer",
                itemclick: toggleDataSeries
            },
            data: [
                {
                    type: "column",
                    name: "Número de usuarios que han visto el tema.",
                    showInLegend: false,
                    indexLabelFontSize: 12,
                    dataPoints: peopleDataPoints
                },
                {
                    type: "area",
                    name: "Porcentaje representativo grupal",
                    markerBorderColor: "white",
                    markerBorderThickness: 2,
                    showInLegend: false,
                    dataPoints: percentagesDataPoints
                }]
        });
        chart.render();
        function addSymbols(e) {
            var suffixes = ["", "K", "M", "B"];
            var order = Math.max(Math.floor(Math.log(e.value) / Math.log(1000)), 0);
            if(order > suffixes.length - 1)
                order = suffixes.length - 1;
            var suffix = suffixes[order];
            return CanvasJS.formatNumber(e.value / Math.pow(1000, order)) + suffix;
        }

        function toggleDataSeries(e) {
            if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            e.chart.render();
        }
    });
</script>

