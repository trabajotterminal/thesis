@php
    $glances_theory_topics = array_column($theory_glances_array, 'name');
@endphp
@foreach($categories_array as $key => $category)
    {{$category}}
    <br>
    @foreach($topics_array[$key] as $topic)
        @if(in_array($topic, $glances_theory_topics))
            <h1>{{$topic}}</h1>
        @else
            {{$topic}}
        @endif

    @endforeach
    <br>
@endforeach