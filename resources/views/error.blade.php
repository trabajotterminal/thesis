@extends('layouts.app')
@section('title', 'Error')

@section('statics-css')
    @include('layouts/statics-css-1')
    <style>
        body{
            margin: 0;
            padding: 0;
            background: #6b4ae3;
            font-family: Arial, Helvetica, sans-serif;
        }
        *{
            margin: 0;
            padding: 0;
        }
        p{
            font-size: 12px;
            color: #373737;
            font-family: Arial, Helvetica, sans-serif;
            line-height: 18px;
        }
        p a{
            color: #218bdc;
            font-size: 12px;
            text-decoration: none;
        }
        a{
            outline: none;
        }
        .f-left{
            float:left;
        }
        .f-right{
            float:right;
        }
        .clear{
            clear: both;
            overflow: hidden;
        }
        #block_error{
            width: 845px;
            height: 384px;
            border: 1px solid #cccccc;
            margin: 72px auto 0;
            -moz-border-radius: 4px;
            -webkit-border-radius: 4px;
            border-radius: 4px;
            background: #fff url(http://www.ebpaidrev.com/systemerror/block.gif) no-repeat 0 51px;
        }
        #block_error div{
            padding: 100px 40px 0 186px;
        }
        #block_error div h2{
            color: #218bdc;
            font-size: 24px;
            display: block;
            padding: 0 0 14px 0;
            border-bottom: 1px solid #cccccc;
            margin-bottom: 12px;
            font-weight: normal;
        }
    </style>
@endsection

@section('menu')
    @include('layouts/menu', ['page' => ''])
@endsection
@section('content')
    <div id="block_error" style="min-height: calc(100vh - 163px);">
        <div>
            <h2>¡Uuuups!</h2>
            <h5>Parece que algo malo malo ocurrió, inténta volver más tarde.</h5>
            <h5>Nuestro equipo recibirá el reporte del error y trabajara en ello lo más pronto posible.</h5>
            <center>
                <img src="{{ URL::asset('/images/barricade.png')}}" style="width:120px;height:120px;margin-top:30px;"/>
                <img src="{{ URL::asset('/images/barricade.png')}}" style="width:120px;height:120px;margin-top:30px;"/>
                <img src="{{ URL::asset('/images/barricade.png')}}" style="width:120px;height:120px;margin-top:30px;"/>
                <img src="{{ URL::asset('/images/barricade.png')}}" style="width:120px;height:120px;margin-top:30px;"/>
            </center>
        </div>
    </div>

@endsection
@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-1')
@endsection





