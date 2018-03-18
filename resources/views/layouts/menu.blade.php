@php
    $menu_classes    = [];
    $menu_classes[0] = $page == 'index' ? 'active' : '';
    $menu_classes[1] = $page == 'category' ? 'active' : '';
    $menu_classes[2] = $page == 'login' ? 'active' : '';
    $menu_classes[3] = $page == 'login' ? 'active' : '';
    $user            = session('user');
    $user_type       = session('user_type');
@endphp
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
                                            <li class="dropdown-submenu"><a href="{{ url('/category/'.$category -> name)}}">{{$category -> name}}</li>
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
                                        <li> <a href="/admin/categories">Categorias</a> </li>
                                        <li> <a href="/admin/topics">Temas</a> </li>
                                    </ul>
                                </li>
                                <li><a href="/admin/statistics" class="dropdown-toggle  {{$menu_classes[2]}}">Ver estadisticas</a></li>
                                <li><a href="/logout" class="dropdown-toggle"  {{$menu_classes[3]}}">Cerrar Sesión</a></li>
                            @endif
                        @else
                            <li class="dropdown"> <a href="#" class="dropdown-toggle {{$menu_classes[1]}}">Categorias</a>
                                <ul class="dropdown-menu" role="menu">
                                    @foreach($categories as $category)
                                        <li class="dropdown-submenu"><a href="{{ url('/category/'.$category -> name)}}">{{$category -> name}}</li>
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