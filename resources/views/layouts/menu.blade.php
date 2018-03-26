@php
    $menu_classes    = [];
    $menu_classes[0] = $page == 'index' ? 'active' : '';
    $menu_classes[1] = $page == 'category' ? 'active' : '';
    $menu_classes[2] = $page == 'login' ? 'active' : '';
    $menu_classes[3] = $page == 'login' ? 'active' : '';
    $user            = session('user');
    $user_type       = session('user_type');
@endphp
<style>
    .navbar-default .dropdown-menu.notify-drop {
        background-color: #fff;
        max-height: 360px;
        overflow-y: scroll;
    }
</style>
<div id="header" style="background-color:#f6f6f6">
    <div class="container orange2" >
        <div class="navbar lightblue-3 navbar-default yamm">
            <div class="navbar-header">
                <button type="button" data-toggle="collapse" data-target="#navbar-collapse-grid" class="navbar-toggle two three"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                <a href="/" class="navbar-brand less-top-padding"><img src="/images/logoEscom.png" style="width:80px;height:50px;"alt=""/></a> </div>
                <div id="navbar-collapse-grid" class="navbar-collapse collapse pull-right">
                    <ul class="nav pink-3 navbar-nav">
                        <li><a href="/" class="dropdown-toggle {{$menu_classes[0]}}">Inicio</a></li>
                        @if($user != null)
                            @if($user_type == 'student')
                                <li class="dropdown"> <a href="#" class="dropdown-toggle {{$menu_classes[1]}}">Categorias</a>
                                    <ul class="dropdown-menu" role="menu">
                                        @foreach($categories as $category)
                                            <li class="dropdown-submenu"><a href="{{ url('/category/'.$category -> approved_name)}}">{{$category -> approved_name}}</li>
                                        @endforeach
                                        <li class="dropdown-submenu mul" style="display:none;">
                                            <a tabindex="-1" href="#">No idea</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="/profile" class="dropdown-toggle {{$menu_classes[2]}}">Perfil</a></li>
                                <li><a href="/logout" class="dropdown-toggle">Cerrar Sesión</a></li>
                            @endif
                            @if($user_type == 'creator')
                                <li class="dropdown"> <a href="#" class="dropdown-toggle  {{$menu_classes[1]}}">Administrar</a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li> <a href="/creator/categories">Categorias</a> </li>
                                        <li> <a href="/creator/topics">Temas</a> </li>
                                    </ul>
                                </li>
                                <li><a href="/creator/statistics" class="dropdown-toggle  {{$menu_classes[2]}}">Ver estadisticas</a></li>
                                <li><a href="/logout" class="dropdown-toggle"  {{$menu_classes[3]}}">Cerrar Sesión</a></li>
                            @endif
                            @if($user_type == 'admin')
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle">Notificaciones (<b>{{count($notifications)}}</b>)</a>
                                        <ul class="dropdown-menu notify-drop">
                                            <div class="drop-content">
                                                @foreach($notifications as $key => $notification)
                                                    <li>
                                                        <div class="col-md-3 col-sm-3 col-xs-3" style="width:60px;height:60px;">
                                                            <center><span style="font-size:40px;text-align: center;vertical-align: middle;line-height: 40px;">{{$sender_names[$key][0]}}</span></center>
                                                        </div>
                                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                                            @php
                                                                $action = "";
                                                                $object = "";
                                                                if($notification -> type == 'A')
                                                                    $action = "agregó";
                                                                if($notification -> type == 'E')
                                                                    $action = 'editó';
                                                                if($notification -> type == 'D')
                                                                    $action = 'eliminó';
                                                                if($notification -> category_id)
                                                                    $object = " una categoria.";
                                                                if($notification -> topic_id)
                                                                    $object = ' un tema';
                                                                if($notification -> reference_id)
                                                                    $object = ' contenido de un tema';
                                                            @endphp
                                                            <p>{{$sender_names[$key]}} {{$action}} {{$object}}</p>
                                                            <p><u><a href="{{url('admin/notification/'.$notification -> id)}}" style="color: #34495e ">Revisar ahora</a></u></p>
                                                            <hr>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </div>
                                        </ul>
                                    </li>
                                    <li><a href="/logout" class="dropdown-toggle"  {{$menu_classes[3]}}">Cerrar Sesión</a></li>
                            @endif
                        @else
                            <li class="dropdown"> <a href="#" class="dropdown-toggle {{$menu_classes[1]}}">Categorias</a>
                                <ul class="dropdown-menu" role="menu">
                                    @foreach($categories as $category)
                                        <li class="dropdown-submenu"><a href="{{ url('/category/'.$category -> approved_name)}}">{{$category -> approved_name}}</li>
                                    @endforeach
                                    <li class="dropdown-submenu mul" style="display:none;">
                                        <a tabindex="-1" href="#">No idea</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="/login" class="dropdown-toggle {{$menu_classes[2]}}">Inicia Sesión</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>