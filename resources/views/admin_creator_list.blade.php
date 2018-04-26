<section>
    <div class="pagenation-holder">
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <h3>Usuario</h3>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" >
                    <h3>Email</h3>
                </div>
            </div>
        </div>
    </div>
</section>
@if($user_names)
    @foreach($user_names as $key => $user)
        <section>
            <div class="pagenation-holder">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <h5 style="white-space: normal;word-break: break-all;">{{$user_names[$key]}}</h5>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <h4 style="white-space: normal;word-break: break-all;">{{$user_emails[$key]}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach
@else
    <section>
        <div class="container">
            <div class="row text-center" style="margin-top:150px;">
                <h1 style="font-size:60px;font-weight: 100">Aún no existen creadores de contenido.</h1>
            </div>
        </div>
    </section>
@endif
