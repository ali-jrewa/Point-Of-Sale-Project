 <aside class="shadow app-sidebar bg-body-secondary" data-bs-theme="dark">
    <!--Sidebar Brand-->
            <div class="sidebar-brand">
                <!--Brand Link--> <a href="./index.html" class="brand-link">
                <!--Brand Image--> <img src="../../dist/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="shadow opacity-75 brand-image">
                <!--Brand Text--> <span class="brand-text fw-light">POS</span>  </a>
            </div>

            <!--Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <!--Sidebar Menu-->
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">


                        {{-- Admin Meun --}}
                        @if(Auth::user()->role->name == 'admin')
                        <li class="nav-item"> <a href="{{ url('/admin/dashboard') }}" class="nav-link @if (Request::segment(2) == 'dashboard') active @endif"> <i class="nav-icon fa fa-dashboard"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-header">MASTER</li>

                        <li class="nav-item"> <a href="{{ route('admin.category.index') }}" class="nav-link @if (Request::segment(2) == 'category') active @endif"> <i class="nav-icon fa fa-cube"></i>
                                <p>Category</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="{{ route('admin.product.index') }}" class="nav-link @if (Request::segment(2) == 'product') active @endif"> <i class="nav-icon fa fa-cubes"></i>
                                <p>Products</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="{{ route('admin.customer.index') }}" class="nav-link @if (Request::segment(2) == 'customer') active @endif"> <i class="nav-icon fa fa-id-card"></i>
                                <p>Customer</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="{{ route('admin.supplier.index') }}" class="nav-link @if (Request::segment(2) == 'supplier') active @endif"> <i class="nav-icon fa fa-truck"></i>
                                <p>Suppliers</p>
                            </a>
                        </li>

                        <li class="nav-header">Transaction</li>

                        <li class="nav-item"> <a href="{{ route('admin.expense-category.index') }}" class="nav-link @if (Request::segment(2) == 'expense-category') active @endif"> <i class="nav-icon fa fa-sitemap"></i>
                                <p>Expenses Category</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="{{ route('admin.expense.index') }}" class="nav-link @if (Request::segment(2) == 'expense') active @endif"> <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                <p>Expenses</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="{{ route('admin.purchase.index') }}" class="nav-link @if (Request::segment(2) == 'purchase') active @endif"> <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                <p>Purchase</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="{{ route('admin.sale.index') }}" class="nav-link @if (Request::segment(2) == 'sale') active @endif"> <i class="nav-icon fa fa-dollar"></i>
                                <p>Sales List</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="" class="nav-link"> <i class="nav-icon fa fa-cart-plus"></i>
                                <p>New Transaction</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="" class="nav-link"> <i class="nav-icon fa fa-cart-arrow-down"></i>
                                <p>Active Transaction</p>
                            </a>
                        </li>

                        <li class="nav-header">Reports</li>

                        <li class="nav-item"> <a href="" class="nav-link"> <i class="nav-icon fa-solid fa-money-bill-trend-up"></i>
                                <p>Income</p>
                            </a>
                        </li>

                        <li class="nav-header">System</li>

                        <li class="nav-item"> <a href="" class="nav-link"> <i class="nav-icon fa fa-users"></i>
                                <p>User</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="" class="nav-link"> <i class="nav-icon fa fa-cogs"></i>
                                <p>Setting</p>
                            </a>
                        </li>
                        @elseif(Auth::user()->role->name == 'admin')
                        <li class="nav-item"> <a href="{{ url('/admin/dashboard') }}" class="nav-link @if (Request::segment(2) == 'dashboard') active @endif"> <i class="nav-icon fa fa-dashboard"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-header">MASTER</li>

                        <li class="nav-item"> <a href="{{ route('admin.category.index') }}" class="nav-link @if (Request::segment(2) == 'category') active @endif"> <i class="nav-icon fa fa-cube"></i>
                                <p>Category</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="{{ route('admin.product.index') }}" class="nav-link @if (Request::segment(2) == 'product') active @endif"> <i class="nav-icon fa fa-cubes"></i>
                                <p>Products</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="{{ route('admin.customer.index') }}" class="nav-link @if (Request::segment(2) == 'customer') active @endif"> <i class="nav-icon fa fa-id-card"></i>
                                <p>Customer</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="{{ route('admin.supplier.index') }}" class="nav-link @if (Request::segment(2) == 'supplier') active @endif"> <i class="nav-icon fa fa-truck"></i>
                                <p>Suppliers</p>
                            </a>
                        </li>

                        <li class="nav-header">Transaction</li>

                        <li class="nav-item"> <a href="{{ route('admin.expense-category.index') }}" class="nav-link @if (Request::segment(2) == 'expense-category') active @endif"> <i class="nav-icon fa fa-sitemap"></i>
                                <p>Expenses Category</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="{{ route('admin.expense.index') }}" class="nav-link @if (Request::segment(2) == 'expense') active @endif"> <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                <p>Expenses</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="{{ route('admin.purchase.index') }}" class="nav-link @if (Request::segment(2) == 'purchase') active @endif"> <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                <p>Purchase</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="{{ route('admin.sale.index') }}" class="nav-link @if (Request::segment(2) == 'sale') active @endif"> <i class="nav-icon fa fa-dollar"></i>
                                <p>Sales List</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="" class="nav-link"> <i class="nav-icon fa fa-cart-plus"></i>
                                <p>New Transaction</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="" class="nav-link"> <i class="nav-icon fa fa-cart-arrow-down"></i>
                                <p>Active Transaction</p>
                            </a>
                        </li>

                        {{-- Cashier Menu --}}
                        @elseif(Auth::user()->role->name == 'cashier')

                        <li class="nav-item active"> <a href="{{ url('user/dashboard') }}" class="nav-link"> <i class="nav-icon bi bi-palette"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>


                        <li class="nav-item"> <a href="" class="nav-link"> <i class="nav-icon bi bi-table"></i>
                                <p>Setting</p>
                            </a>
                        </li>

                        @endif
                    </ul>
                </nav>
            </div>
        </aside>
