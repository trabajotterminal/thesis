@php
    $glances_theory_topics = array_column($theory_glances_array, 'name');
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

                                @if(in_array($topic, $glances_theory_topics))
                                    <div class="iconbox-medium round" style="background-color:#2ecc71;">
                                        <i class="fas fa-check"></i>
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <br>
                                    <h5>{{$topic}}</h5>
                                @else
                                    <div class="iconbox-medium round" style="background-color:#e74c3c;">
                                        <i class="fas fa-eye-slash" style="color:white;"></i>
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
<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>