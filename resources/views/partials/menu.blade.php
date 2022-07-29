<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">
                        <a href="{{ route('my_account') }}"><img src="/{{Auth::user()->photo_by}}/{{Auth::user()->photo}}" width="38" height="38" class="rounded-circle" alt="photo"></a>
                    </div>

                    <div class="media-body">
                        <div class="media-title font-weight-semibold">{{ Auth::user()->name }}</div>
                        <div class="font-size-xs opacity-50">
                            <i class="icon-user font-size-sm"></i> &nbsp;{{ ucwords(str_replace('_', ' ', Auth::user()->user_type->title)) }}
                        </div>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="{{ route('my_account') }}" class="text-white"><i class="icon-cog3"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                @if(Qs::userIsAdmin())
                    <!-- Main -->
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ (Route::is('dashboard')) ? 'active' : '' }}">
                            <i class="icon-home4"></i>
                            <span>Edit Whatsapp Message</span>
                        </a>
                    </li>
                    
                        
                    <li class="nav-item">
                        <a href="{{ route('phone_numbers.index') }}" 
                            class="nav-link {{ in_array(Route::currentRouteName(), ['classes.index', 'class_manage', 'class_subject_manage', 'subject_students_manage', 'classes.edit']) ? 'active' : '' }}">
                            <i class="icon-windows2"></i> <span> CRUD Number</span>
                        </a>
                    </li>
                @endif
                </ul>
            </div>
        </div>
</div>
