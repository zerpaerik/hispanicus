@inject('request', 'Illuminate\Http\Request')
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu">

            <li class="{{ $request->segment(1) == 'home' ? 'active' : '' }}">
                <a href="{{ url('/') }}">
                    <i class="fa fa-wrench"></i>
                    <span class="title">@lang('global.app_dashboard')</span>
                </a>
            </li>
            
            @can('users_manage')
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span class="title">@lang('global.user-management.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li class="{{ $request->segment(2) == 'abilities' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.abilities.index') }}">
                            <i class="fa fa-briefcase"></i>
                            <span class="title">
                                @lang('global.abilities.title')
                            </span>
                        </a>
                    </li>
                    <li class="{{ $request->segment(2) == 'roles' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.roles.index') }}">
                            <i class="fa fa-briefcase"></i>
                            <span class="title">
                                @lang('global.roles.title')
                            </span>
                        </a>
                    </li>
                    <li class="{{ $request->segment(2) == 'users' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fa fa-user"></i>
                            <span class="title">
                                @lang('global.users.title')
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            <!-- API CLIENTS -->

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-sign-in"></i>
                    <span class="title">Api Clients</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>                    
                </a>
                <ul class="treeview-menu">
                    <li class="{{ $request->segment(2) == 'clients' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.clients') }}">
                            <i class="fa fa-cubes"></i>
                            <span class="title">Oauth Clients</span>
                        </a>
                    </li>

                    <li class="{{ $request->segment(2) == 'personal' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.personal') }}">
                            <i class="fa fa-exchange"></i>
                            <span class="title">Personal Access Tokens</span>
                        </a>
                    </li>

                </ul>
            </li>
            
            <!--VERBOS-->

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-font"></i>
                    <span class="title">Verbos</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>                    
                </a>
                <ul class="treeview-menu">
                    <li class="{{ $request->segment(2) == 'verbos_create' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.verbos.create') }}">
                            <i class="fa fa-plus"></i>
                            <span class="title">Crear Verbo</span>
                        </a>
                    </li>

                    <li class="{{ $request->segment(2) == 'dict_create' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.verbos.dict') }}">
                            <i class="fa fa-book"></i>
                            <span class="title">Crear Diccionario</span>
                        </a>
                    </li>
                                        
                </ul>
            </li>

            <li class="{{ $request->segment(1) == 'change_password' ? 'active' : '' }}">
                <a href="{{ route('auth.change_password') }}">
                    <i class="fa fa-key"></i>
                    <span class="title">Change password</span>
                </a>
            </li>

            <li>
                <a href="#logout" onclick="$('#logout').submit();">
                    <i class="fa fa-arrow-left"></i>
                    <span class="title">@lang('global.app_logout')</span>
                </a>
            </li>
        </ul>
    </section>
</aside>
{!! Form::open(['route' => 'auth.logout', 'style' => 'display:none;', 'id' => 'logout']) !!}
<button type="submit">@lang('global.logout')</button>
{!! Form::close() !!}
