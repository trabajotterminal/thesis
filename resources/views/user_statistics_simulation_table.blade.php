@php
    $glances_simulation_topics = array_column($simulation_glances_array, 'approved_name');
    $seen_topics = count($glances_simulation_topics);
    $percentage = $total_topics > 0 ? $seen_topics * 100 / $total_topics : 0;
@endphp

<style>body{overflow-x: hidden;}</style>
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
                    @foreach($topics_array[$key] as $topic)
                        <div class="col-md-2">
                            <div class="feature-box-103 text-center bmargin">

                                @if(in_array($topic, $glances_simulation_topics))
                                    <div class="iconbox-medium round" style="background-color:#2ecc71;">
                                        <span class="fa-stack">
                                            <i class="fa fa-check fa-stack-1x"></i>
                                            <i class="fa fa-check fa-stack-1x"></i>
                                        </span>
                                    </div>
                                    <br>
                                    <h5>{{$topic}}</h5>
                                @else
                                    <div class="iconbox-medium round" style="background-color:#e74c3c;">

                                        <span class="fa-stack fa-lg">
                                          <i class="fas fa-eye-slash fa-stack-1x" style="color:white;"></i>
                                        </span>
                                    </div>
                                    <h5>{{$topic}}</h5>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="sh-divider-line doubble light  margin"></div>
    @endforeach
</div>
<h1 class="margin-left-3 margin-top1">Estadisticas generales</h1>
<br>
<div id="chartContainerSimulation" style="height: 300px; width: 100%;margin-top:60px;"></div>
<script>
    $(document).ready(function(){
        CanvasJS.addColorSet("greenShades",
            [
                "#2ECC71",
                "#CD6155",
            ]
        );
        var chart = new CanvasJS.Chart("chartContainerSimulation", {
            colorSet: "greenShades",
            animationEnabled: true,
            title:{
                text: "",
                horizontalAlign: "left"
            },
            data: [{
                type: "doughnut",
                startAngle: 60,
                //innerRadius: 60,
                indexLabelFontSize: 17,
                indexLabel: "{label} - #percent%",
                toolTipContent: "<b>{label}:</b> {y} (#percent%)",
                dataPoints: [
                    { y: <?php echo $percentage ?>, label: "Simulaciones vistas" },
                    { y: 100 - <?php echo $percentage ?>, label: "Simulaciones no vistas" },
                ]
            }]
        });
        chart.render();
    });
</script>

