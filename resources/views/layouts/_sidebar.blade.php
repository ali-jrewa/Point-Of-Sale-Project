<aside class="shadow app-sidebar bg-body-secondary" data-bs-theme="dark">
    <!--Sidebar Brand-->
     @if(Auth::user()->role->name == 'admin')
    <div class="sidebar-brand">
        <a href="{{ url('/admin/dashboard') }}" class="brand-link">
            <img src="{{ asset('/dist/assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="shadow opacity-75 brand-image">
            <span class="brand-text fw-light">{{ __('sidebar.brand_text') }}</span>
        </a>
    </div>
    @elseif ( Auth::user()->role->name == 'manager')
    <div class="sidebar-brand">
        <a href="{{ url('/manager/dashboard') }}" class="brand-link">
            <img src="{{ asset('/dist/assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="shadow opacity-75 brand-image">
            <span class="brand-text fw-light">{{ __('sidebar.brand_text') }}</span>
        </a>
    </div>
    @elseif(Auth::user()->role->name == 'cashier')
    <div class="sidebar-brand">
        <a href="{{ url('/cashier/dashboard') }}" class="brand-link">
            <img src="{{ asset('/dist/assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="shadow opacity-75 brand-image">
            <span class="brand-text fw-light">{{ __('sidebar.brand_text') }}</span>
        </a>
    </div>
    @endif

    <!--Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                {{-- Admin Menu --}}
                @if(Auth::user()->role->name == 'admin')
                <li class="nav-item">
                    <a href="{{ url('/admin/dashboard') }}" class="nav-link @if (Request::segment(2) == 'dashboard') active @endif">
                        <i class="nav-icon fa fa-dashboard"></i>
                        <p>{{ __('sidebar.dashboard') }}</p>
                    </a>
                </li>

                <li class="nav-header">{{ __('sidebar.master') }}</li>

                <li class="nav-item">
                    <a href="{{ route('admin.category.index') }}" class="nav-link @if (Request::segment(2) == 'category') active @endif">
                        <i class="nav-icon fa fa-cube"></i>
                        <p>{{ __('sidebar.category') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.product.index') }}" class="nav-link @if (Request::segment(2) == 'product') active @endif">
                        <i class="nav-icon fa fa-cubes"></i>
                        <p>{{ __('sidebar.products') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.customer.index') }}" class="nav-link @if (Request::segment(2) == 'customer') active @elseif(Request::segment(3) == 'customer')  active @endif">
                        <i class="nav-icon fa fa-id-card"></i>
                        <p>{{ __('sidebar.customer') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.supplier.index') }}" class="nav-link @if (Request::segment(2) == 'supplier') active @elseif(Request::segment(3) == 'supplier')  active @endif">
                        <i class="nav-icon fa fa-truck"></i>
                        <p>{{ __('sidebar.suppliers') }}</p>
                    </a>
                </li>

                <li class="nav-header">{{ __('sidebar.transaction') }}</li>

                <li class="nav-item">
                    <a href="{{ route('admin.expense-category.index') }}" class="nav-link @if (Request::segment(2) == 'expense-category') active @endif">
                        <i class="nav-icon fa fa-sitemap"></i>
                        <p>{{ __('sidebar.expense_category') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.expense.index') }}" class="nav-link @if (Request::segment(2) == 'expense') active @endif">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>{{ __('sidebar.expenses') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.purchase.index') }}" class="nav-link @if (Request::segment(2) == 'purchase') active @endif">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>{{ __('sidebar.purchase') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.sale.index') }}" class="nav-link @if (Request::segment(2) == 'sale') active @endif">
                        <i class="nav-icon fa fa-cart-plus"></i>
                        <p>{{ __('sidebar.sales_list') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.payment.index') }}" class="nav-link @if (Request::segment(2) == 'payment') active @endif">
                        <i class="nav-icon fa fa-dollar"></i>
                        <p>{{ __('sidebar.payments') }}</p>
                    </a>
                </li>

                <li class="nav-header">{{ __('sidebar.reports') }}</li>

                <li class="nav-item" id="reports-menu" class="@if (Request::segment(2) == 'report') active @elseif(Request::segment(3) == 'report')  active @endif">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fa-solid fa-chart-line"></i>
                        <p>
                            {{ __('sidebar.reports') }}
                            <i class="right nav-arrow fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.report.sales') }}" class="nav-link @if (Request::segment(3) == 'sales') active @endif">
                                <i class="nav-icon fa fa-cart-plus"></i>
                                <p>{{ __('sidebar.sales_report') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.report.purchases') }}" class="nav-link @if (Request::segment(3) == 'purchases') active @endif">
                                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                <p>{{ __('sidebar.purchase_report') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.report.profit-loss') }}" class="nav-link @if (Request::segment(3) == 'profit-loss') active @endif">
                                <i class="nav-icon fa-solid fa-money-bill-trend-up"></i>
                                <p>{{ __('sidebar.profit_loss') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.report.inputs-outputs') }}" class="nav-link @if (Request::segment(3) == 'inputs-outputs') active @endif">
                                <i class="nav-icon fa-solid fa-exchange-alt"></i>
                                <p>{{ __('sidebar.inputs_outputs_report') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.report.expenses') }}" class="nav-link @if (Request::segment(3) == 'expenses') active @endif">
                                <i class="nav-icon fa fa-receipt"></i>
                                <p>{{ __('sidebar.expense_report') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.report.payments') }}" class="nav-link @if (Request::segment(3) == 'payments') active @endif">
                                <i class="nav-icon fa fa-dollar"></i>
                                <p>{{ __('sidebar.payment_report') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.report.refunds') }}" class="nav-link @if (Request::segment(3) == 'refunds') active @endif">
                                <i class="nav-icon fa fa-rotate-left"></i>
                                <p>{{ __('sidebar.refund_report') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.report.stock') }}" class="nav-link @if (Request::segment(3) == 'stock') active @endif">
                                <i class="nav-icon fa fa-boxes-stacked"></i>
                                <p>{{ __('sidebar.stock_report') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.report.low-stock') }}" class="nav-link @if (Request::segment(3) == 'low-stock') active @endif">
                                <i class="nav-icon fa-solid fa-triangle-exclamation"></i>
                                <p>{{ __('sidebar.low_stock_alert') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.report.top-customers') }}" class="nav-link @if (Request::segment(3) == 'top-customers') active @endif">
                                <i class="nav-icon fa fa-id-card"></i>
                                <p>{{ __('sidebar.top_customers') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.report.due') }}" class="nav-link @if (Request::segment(3) == 'due') active @endif">
                                <i class="nav-icon fa-solid fa-hourglass-half"></i>
                                <p>{{ __('sidebar.due_outstanding') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">{{ __('sidebar.system') }}</li>

                <li class="nav-item">
                    <a href="{{ route('admin.user.index') }}" class="nav-link @if (Request::segment(2) == 'user') active @endif">
                        <i class="nav-icon fa fa-users"></i>
                        <p>{{ __('sidebar.users') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.account.index') }}" class="nav-link @if (Request::segment(2) == 'account') active @endif">
                        <i class="nav-icon fa fa-user"></i>
                        <p>{{ __('sidebar.my_account') }}</p>
                    </a>
                </li>

                {{-- Manager Menu --}}
                @elseif(Auth::user()->role->name == 'manager')
                <li class="nav-item">
                    <a href="{{ url('/admin/dashboard') }}" class="nav-link @if (Request::segment(2) == 'dashboard') active @endif">
                        <i class="nav-icon fa fa-dashboard"></i>
                        <p>{{ __('sidebar.dashboard') }}</p>
                    </a>
                </li>

                <li class="nav-header">{{ __('sidebar.master') }}</li>

                <li class="nav-item">
                    <a href="{{ route('manager.category.index') }}" class="nav-link @if (Request::segment(2) == 'category') active @endif">
                        <i class="nav-icon fa fa-cube"></i>
                        <p>{{ __('sidebar.category') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('manager.product.index') }}" class="nav-link @if (Request::segment(2) == 'product') active @endif">
                        <i class="nav-icon fa fa-cubes"></i>
                        <p>{{ __('sidebar.products') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('manager.customer.index') }}" class="nav-link @if (Request::segment(2) == 'customer') active @elseif(Request::segment(3) == 'customer')  active @endif">
                        <i class="nav-icon fa fa-id-card"></i>
                        <p>{{ __('sidebar.customer') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('manager.supplier.index') }}" class="nav-link @if (Request::segment(2) == 'supplier') active @elseif(Request::segment(3) == 'supplier')  active @endif">
                        <i class="nav-icon fa fa-truck"></i>
                        <p>{{ __('sidebar.suppliers') }}</p>
                    </a>
                </li>

                <li class="nav-header">{{ __('sidebar.transaction') }}</li>

                <li class="nav-item">
                    <a href="{{ route('manager.expense-category.index') }}" class="nav-link @if (Request::segment(2) == 'expense-category') active @endif">
                        <i class="nav-icon fa fa-sitemap"></i>
                        <p>{{ __('sidebar.expense_category') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('manager.expense.index') }}" class="nav-link @if (Request::segment(2) == 'expense') active @endif">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>{{ __('sidebar.expenses') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('manager.purchase.index') }}" class="nav-link @if (Request::segment(2) == 'purchase') active @endif">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>{{ __('sidebar.purchase') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('manager.sale.index') }}" class="nav-link @if (Request::segment(2) == 'sale') active @endif">
                        <i class="nav-icon fa fa-cart-plus"></i>
                        <p>{{ __('sidebar.sales_list') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('manager.payment.index') }}" class="nav-link @if (Request::segment(2) == 'payment') active @endif">
                        <i class="nav-icon fa fa-dollar"></i>
                        <p>{{ __('sidebar.payments') }}</p>
                    </a>
                </li>

                <li class="nav-header">{{ __('sidebar.reports') }}</li>

                <li class="nav-item" id="reports-menu" class="@if (Request::segment(2) == 'report') active @elseif(Request::segment(3) == 'report')  active @endif">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-solid fa-chart-line"></i>
                        <p>
                            {{ __('sidebar.reports') }}
                            <i class="right nav-arrow fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('manager.report.sales') }}" class="nav-link @if (Request::segment(3) == 'sales') active @endif">
                                <i class="nav-icon fa fa-cart-plus"></i>
                                <p>{{ __('sidebar.sales_report') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manager.report.purchases') }}" class="nav-link @if (Request::segment(3) == 'purchases') active @endif">
                                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                <p>{{ __('sidebar.purchase_report') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manager.report.profit-loss') }}" class="nav-link @if (Request::segment(3) == 'profit-loss') active @endif">
                                <i class="nav-icon fa-solid fa-money-bill-trend-up"></i>
                                <p>{{ __('sidebar.profit_loss') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manager.report.inputs-outputs') }}" class="nav-link @if (Request::segment(3) == 'inputs-outputs') active @endif">
                                <i class="nav-icon fa-solid fa-exchange-alt"></i>
                                <p>{{ __('sidebar.inputs_outputs_report') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manager.report.expenses') }}" class="nav-link @if (Request::segment(3) == 'expenses') active @endif">
                                <i class="nav-icon fa fa-receipt"></i>
                                <p>{{ __('sidebar.expense_report') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manager.report.payments') }}" class="nav-link @if (Request::segment(3) == 'payments') active @endif">
                                <i class="nav-icon fa fa-dollar"></i>
                                <p>{{ __('sidebar.payment_report') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manager.report.refunds') }}" class="nav-link @if (Request::segment(3) == 'refunds') active @endif">
                                <i class="nav-icon fa fa-rotate-left"></i>
                                <p>{{ __('sidebar.refund_report') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manager.report.stock') }}" class="nav-link @if (Request::segment(3) == 'stock') active @endif">
                                <i class="nav-icon fa fa-boxes-stacked"></i>
                                <p>{{ __('sidebar.stock_report') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manager.report.low-stock') }}" class="nav-link @if (Request::segment(3) == 'low-stock') active @endif">
                                <i class="nav-icon fa-solid fa-triangle-exclamation"></i>
                                <p>{{ __('sidebar.low_stock_alert') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manager.report.top-customers') }}" class="nav-link @if (Request::segment(3) == 'top-customers') active @endif">
                                <i class="nav-icon fa fa-id-card"></i>
                                <p>{{ __('sidebar.top_customers') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manager.report.due') }}" class="nav-link @if (Request::segment(3) == 'due') active @endif">
                                <i class="nav-icon fa-solid fa-hourglass-half"></i>
                                <p>{{ __('sidebar.due_outstanding') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">{{ __('sidebar.system') }}</li>

                <li class="nav-item">
                    <a href="{{ route('manager.account.index') }}" class="nav-link @if (Request::segment(2) == 'account') active @endif">
                        <i class="nav-icon fa fa-user"></i>
                        <p>{{ __('sidebar.my_account') }}</p>
                    </a>
                </li>

                {{-- Cashier Menu --}}
                @elseif(Auth::user()->role->name == 'cashier')

                <li class="nav-item active">
                    <a href="{{ url('cashier/dashboard') }}" class="nav-link">
                        <i class="nav-icon bi bi-palette"></i>
                        <p>{{ __('sidebar.dashboard') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('cashier.sale.index') }}" class="nav-link @if (Request::segment(2) == 'sale') active @endif">
                        <i class="nav-icon fa fa-dollar"></i>
                        <p>{{ __('sidebar.sales_list') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('cashier.payment.index') }}" class="nav-link @if (Request::segment(2) == 'payment') active @endif">
                        <i class="nav-icon fa fa-dollar"></i>
                        <p>{{ __('sidebar.payments') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('cashier.account.index') }}" class="nav-link @if (Request::segment(2) == 'account') active @endif">
                        <i class="nav-icon fa fa-user"></i>
                        <p>{{ __('sidebar.my_account') }}</p>
                    </a>
                </li>

                @endif
            </ul>
        </nav>
    </div>
</aside>
