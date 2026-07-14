<nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item"> <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"> <i class="bi bi-list"></i> </a> </li>
                    <li class="nav-item d-none d-md-block"> <a href="#" class="nav-link">Home</a> </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <!--Fullscreen Toggle-->
                    <li class="nav-item"> <a class="nav-link" href="#" data-lte-toggle="fullscreen"> <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i> <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i> </a> </li>
                     <!--User Menu Dropdown-->
                    <li class="nav-item dropdown user-menu"> <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"> <img src="{{ Auth::user()->account_image ? asset('storage/' . Auth::user()->account_image) : asset('dist/assets/img/adminLteLogo.png') }}" class="shadow user-image rounded-circle" alt="User Image"> <span class="d-none d-md-inline">{{ Auth::user()->name }}</span> </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-primary"> <img src="{{ Auth::user()->account_image ? asset('storage/' . Auth::user()->account_image) : asset('dist/assets/img/adminLteLogo.png') }}"
                                class="shadow rounded-circle" alt="User Image">
                                <p>
                                   {{ Auth::user()->email }}
                                    <small>Member since {{ Auth::user()->created_at }}</small>
                                </p>
                            </li>
                             <!--Menu Footer-->
                            <li class="user-footer"><a  href="{{ url('/logout') }}" class="btn btn-default btn-flat float-end">Sign out</a> </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
