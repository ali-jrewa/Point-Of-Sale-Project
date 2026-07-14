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

                        <li class="nav-item"> <a href="{{ route('admin.sale.index') }}" class="nav-link @if (Request::segment(2) == 'sale') active @endif"> <i class="nav-icon fa fa-cart-plus"></i>
                                <p>Sales List</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="{{ route('admin.payment.index') }}" class="nav-link @if (Request::segment(2) == 'payment') active @endif"> <i class="nav-icon fa fa-dollar"></i>
                                <p>Payments</p>
                            </a>
                        </li>

                        <li class="nav-header">Reports</li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-chart-line"></i>
                                <p>
                                    Reports
                                    <i class="right nav-arrow fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.report.sales') }}" class="nav-link @if (Request::segment(3) == 'sales') active @endif">
                                        <i class="nav-icon fa fa-cart-plus"></i>
                                        <p>Sales Report</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.report.purchases') }}" class="nav-link @if (Request::segment(3) == 'purchases') active @endif">
                                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                        <p>Purchase Report</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.report.profit-loss') }}" class="nav-link @if (Request::segment(3) == 'profit-loss') active @endif">
                                        <i class="nav-icon fa-solid fa-money-bill-trend-up"></i>
                                        <p>Profit &amp; Loss</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.report.expenses') }}" class="nav-link @if (Request::segment(3) == 'expenses') active @endif">
                                        <i class="nav-icon fa fa-receipt"></i>
                                        <p>Expense Report</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.report.payments') }}" class="nav-link @if (Request::segment(3) == 'payments') active @endif">
                                        <i class="nav-icon fa fa-dollar"></i>
                                        <p>Payment Report</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.report.refunds') }}" class="nav-link @if (Request::segment(3) == 'refunds') active @endif">
                                        <i class="nav-icon fa fa-rotate-left"></i>
                                        <p>Refund Report</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.report.stock') }}" class="nav-link @if (Request::segment(3) == 'stock') active @endif">
                                        <i class="nav-icon fa fa-boxes-stacked"></i>
                                        <p>Stock Report</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.report.low-stock') }}" class="nav-link @if (Request::segment(3) == 'low-stock') active @endif">
                                        <i class="nav-icon fa-solid fa-triangle-exclamation"></i>
                                        <p>Low Stock Alert</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.report.top-customers') }}" class="nav-link @if (Request::segment(3) == 'top-customers') active @endif">
                                        <i class="nav-icon fa fa-id-card"></i>
                                        <p>Top Customers</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.report.due') }}" class="nav-link @if (Request::segment(3) == 'due') active @endif">
                                        <i class="nav-icon fa-solid fa-hourglass-half"></i>
                                        <p>Due / Outstanding</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-header">System</li>

                        <li class="nav-item"> <a href="{{ route('admin.user.index') }}" class="nav-link @if (Request::segment(2) == 'user') active @endif"> <i class="nav-icon fa fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="{{ route('admin.account.index') }}" class="nav-link @if (Request::segment(2) == 'account') active @endif"> <i class="nav-icon fa fa-user"></i>
                                <p>My Account</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="" class="nav-link"> <i class="nav-icon fa fa-cogs"></i>
                                <p>Setting</p>
                            </a>
                        </li>
                        {{-- Admin Meun --}}
                        @elseif(Auth::user()->role->name == 'manager')
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

                        <li class="nav-item"> <a href="{{ route('admin.sale.index') }}" class="nav-link @if (Request::segment(2) == 'sale') active @endif"> <i class="nav-icon fa fa-cart-plus"></i>
                                <p>Sales List</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="{{ route('admin.payment.index') }}" class="nav-link @if (Request::segment(2) == 'payment') active @endif""> <i class="nav-icon fa fa-dollar"></i>
                                <p>Payments</p>
                            </a>
                        </li>

                        {{-- Cashier Menu --}}
                        @elseif(Auth::user()->role->name == 'cashier')

                        <li class="nav-item active"> <a href="{{ url('user/dashboard') }}" class="nav-link"> <i class="nav-icon bi bi-palette"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-item"> <a href="{{ route('user.sale.index') }}" class="nav-link @if (Request::segment(2) == 'sale') active @endif"> <i class="nav-icon fa fa-dollar"></i>
                                <p>Sales List</p>
                            </a>
                        </li>

                        {{--
                         <li class="nav-item"> <a href="{{ route('user.transaction.create') }}" class="nav-link @if (Request::segment(2) == 'transaction') active @endif"> <i class="nav-icon fa fa-cart-plus"></i>
                                <p>Payments</p>
                            </a>
                        </li> 
                         --}}

                        <li class="nav-item"> <a href="{{ route('cashier.account.index') }}" class="nav-link @if (Request::segment(2) == 'account') active @endif"> <i class="nav-icon fa fa-user"></i>
                                <p>My Account</p>
                            </a>
                        </li>

                        @endif
                    </ul>
                </nav>
            </div>
        </aside>
