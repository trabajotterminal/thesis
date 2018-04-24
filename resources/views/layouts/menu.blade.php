@php
    $menu_classes       = [];
    $menu_classes[0]    = $page == 'index' ? 'active' : '';
    $menu_classes[1]    = $page == 'category' ? 'active' : '';
    $menu_classes[2]    = $page == 'login' ? 'active' : '';
    $menu_classes[3]    = $page == 'login' ? 'active' : '';
    $user               = session('user');
    $user_type          = session('user_type');
    $notification_count = count($notifications);
    if($unread_notifications){
        $unread_notifications = '('.$unread_notifications.')';
    }else{
        $unread_notifications = '';
    }
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
                <a href="{{URL('/')}}" class="navbar-brand less-top-padding"><img src="{{URL::asset('/images/logo-header.png')}}" style="max-width:276px;" alt=""/></a> </div>
                <div id="navbar-collapse-grid" class="navbar-collapse collapse pull-right">
                    <ul class="nav pink-3 navbar-nav">
                        <li><a href="{{URL('/')}}" class="dropdown-toggle {{$menu_classes[0]}}">Inicio</a></li>
                        @if($user != null)
                            @if($user_type == 'student')
                                <li class="dropdown"> <a href="#" class="dropdown-toggle {{$menu_classes[1]}}">Categorias</a>
                                    <ul class="dropdown-menu" role="menu">
                                        @foreach($categories as $category)
                                            <li class="dropdown-submenu"><a href="{{ url('/category/'.$category -> approved_name)}}">{{$category -> approved_name}}</li>
                                        @endforeach
                                        @if(count($categories) == 0)
                                                <li class="dropdown-submenu"><a href="#">Aún no hay categorias disponibles</a></li>
                                        @endif
                                        <li class="dropdown-submenu mul" style="display:none;">
                                            <a tabindex="-1" href="#">No idea</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="{{URL('/profile')}}" class="dropdown-toggle {{$menu_classes[2]}}">Perfil</a></li>
                                <li><a href="{{URL('/logout')}}" class="dropdown-toggle">Cerrar Sesión</a></li>
                            @endif
                            @if($user_type == 'creator')
                                <li class="dropdown"> <a href="#" class="dropdown-toggle  {{$menu_classes[1]}}">Administrar</a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li> <a href="{{URL('/creator/categories')}}">Mis categorias</a> </li>
                                        <li> <a href="{{URL('/creator/topics')}}">Mis temas</a> </li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle">Notificaciones <b>{{$unread_notifications}}</b></a>
                                    <ul class="dropdown-menu notify-drop">
                                        <div class="drop-content">
                                            @if($notification_count == '')
                                                <li style="margin-left:20px;">Sin notificaciones</li>
                                            @else
                                                @foreach($notifications as $key => $notification)
                                                    <li style="overflow-x: hidden;">
                                                        @if($notification -> seen == false)
                                                            <div class="row" style="background-color: #edf2fa;">
                                                        @else
                                                            <div class="row">
                                                        @endif
                                                            <div class="col-md-3 col-sm-3 col-xs-3" style="width:60px;height:60px;margin-left:10px;">
                                                                @if($notification -> type == 'MP')
                                                                    <center><img src="{{ URL::asset('/images/accepted.png')}}" style="width:25px;height:25px;margin-top:10px;"/></center>
                                                                @endif
                                                                @if($notification -> type == 'MN')
                                                                    <center><img src="{{ URL::asset('/images/cross.png')}}" style="width:25px;height:25px;margin-top:10px;"/></center>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-9 col-sm-9 col-xs-9">
                                                                @php
                                                                    $action = "";
                                                                    $object = "";
                                                                    $image  = "";
                                                                    if($notification -> type == 'MP'){
                                                                        $action = "aprobo";
                                                                    }
                                                                    if($notification -> type == 'MN'){
                                                                        $action = "rechazó";
                                                                    }
                                                                    if($notification -> additional_params == 'E'){
                                                                        $action.= " la edición";
                                                                    }
                                                                    if($notification -> additional_params == 'A'){
                                                                        $action.= " la creación";
                                                                    }
                                                                    if($notification -> additional_params == 'D'){
                                                                        $action.= " la eliminación";
                                                                    }

                                                                    if($notification -> topic_id){
                                                                        $object = " de un tema";
                                                                    }
                                                                    if($notification -> category_id){
                                                                        $object = "de una categoria";
                                                                    }
                                                                    if($notification -> reference_id){
                                                                        $object = "del contenido de un tema";
                                                                    }
                                                                @endphp
                                                                <p>
                                                                    {{$sender_names[$key]}} <span>{{$action}}</span> {{$object}}.
                                                                </p>
                                                                <p><u><a href="{{url('creator/notification/'.$notification -> id)}}" style="color: #34495e ">Ver más</a></u></p>
                                                                <hr>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </div>
                                    </ul>
                                </li>
                                <li><a href="{{URL('/logout')}}" class="dropdown-toggle"  {{$menu_classes[3]}}">Cerrar Sesión</a></li>
                            @endif
                            @if($user_type == 'admin')
                                    <li class="dropdown"> <a href="#" class="dropdown-toggle  {{$menu_classes[1]}}">Administrar</a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li> <a href="{{URL('/admin/creators')}}">Creadores de contenido</a> </li>
                                        </ul>
                                    </li>
                                    <li><a href="{{URL('/admin/statistics')}}" class="dropdown-toggle  {{$menu_classes[2]}}">Ver estadisticas</a></li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle">Notificaciones <b>{{$unread_notifications}}</b></a>
                                        <ul class="dropdown-menu notify-drop">
                                            <div class="drop-content">
                                                @if($notification_count == '')
                                                    <li style="margin-left:20px;">Sin notificaciones</li>
                                                @else
                                                    @foreach($notifications as $key => $notification)
                                                        <li>
                                                            <div class="col-md-2 col-sm-2 col-xs-2" style="width:30px;height:30px;">
                                                                <center><span style="font-size:40px;text-align: center;vertical-align: middle;line-height: 40px;">{{$sender_names[$key][0]}}</span></center>
                                                            </div>
                                                            <div class="col-md-10 col-sm-10 col-xs-10">
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
                                                                    if($notification -> reference_id){
                                                                        $object = ' contenido de un tema';
                                                                    }
                                                                @endphp
                                                                <p>{{$sender_names[$key]}} {{$action}} {{$object}}</p>
                                                                @if($notification -> reference_id)
                                                                    @if($reference_type[$key] == 'T')
                                                                        <p><u><a href="{{url('admin/notification/theory/'.$notification -> id)}}" style="color: #34495e ">Revisar ahora</a></u></p>
                                                                    @endif
                                                                    @if($reference_type[$key] == 'C')
                                                                        <p><u><a href="{{url('admin/notification/questionnaire/'.$notification -> id)}}" style="color: #34495e ">Revisar ahora</a></u></p>
                                                                    @endif
                                                                    @if($reference_type[$key] == 'S')
                                                                        <p><u><a href="{{url('admin/notification/simulation/'.$notification -> id)}}" style="color: #34495e ">Revisar ahora</a></u></p>
                                                                    @endif
                                                                @else
                                                                    <p><u><a href="{{url('admin/notification/'.$notification -> id)}}" style="color: #34495e ">Revisar ahora</a></u></p>
                                                                @endif

                                                                <hr>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </ul>
                                    </li>
                                    <li><a href="{{URL('/logout')}}" class="dropdown-toggle"  {{$menu_classes[3]}}">Cerrar Sesión</a></li>
                            @endif
                        @else
                            <li class="dropdown"> <a href="#" class="dropdown-toggle {{$menu_classes[1]}}">Categorias</a>
                                <ul class="dropdown-menu" role="menu">
                                    @foreach($categories as $category)
                                        <li class="dropdown-submenu"><a href="{{ url('/category/'.$category -> approved_name)}}">{{$category -> approved_name}}</li>
                                    @endforeach
                                    @if(count($categories) == 0)
                                        <li class="dropdown-submenu"><a href="#">Aún no hay categorias disponibles</a></li>
                                    @endif
                                    <li class="dropdown-submenu mul" style="display:none;">
                                        <a tabindex="-1" href="#">No idea</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="{{URL('/login')}}" class="dropdown-toggle {{$menu_classes[2]}}">Inicia Sesión</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>