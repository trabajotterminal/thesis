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
                                <h1>{{$topic}}</h1>
                                <h4>{{$percentages[$topic]}}</h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="sh-divider-line doubble light  margin"></div>
    @endforeach
</div>
<h3 class="margin-left-3 margin-top1">Estadisticas generales - Teoría.</h3>
<div id="chartContainerTheory" style="height: 300px; width: 100%;margin-top:60px;"></div>
<script>
    $(document).ready(function(){
        CanvasJS.addColorSet("greenShades",
            [
                "#2ECC71",
                "#CD6155",
            ]
        );
        var chart = new CanvasJS.Chart("chartContainerTheory", {
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
                    { y: 100, label: "Temas vistos" },
                    { y: 100 - 100, label: "Temas sin revisar" },
                ]
            }]
        });
        chart.render();
    });
</script>

