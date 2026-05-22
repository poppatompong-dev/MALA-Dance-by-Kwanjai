@php
    $route = request()->route()->getName();
@endphp

<div class="sidebar">
    <nav class="mt-2" aria-label="เมนูหลังร้าน">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @can('dashboard_view')
                <li class="nav-item">
                    <a href="{{ route('backend.admin.dashboard') }}" class="nav-link {{ $route === 'backend.admin.dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt" aria-hidden="true"></i>
                        <p>แดชบอร์ด</p>
                    </a>
                </li>
            @endcan

            @can('sale_create')
                <li class="nav-item">
                    <a href="{{ route('backend.admin.cart.index') }}" class="nav-link {{ $route === 'backend.admin.cart.index' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cart-plus" aria-hidden="true"></i>
                        <p>ขายหน้าร้าน</p>
                    </a>
                </li>
            @endcan

            <li class="nav-item">
                <a href="{{ route('backend.admin.manuals.index') }}" class="nav-link {{ $route === 'backend.admin.manuals.index' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-book-open" aria-hidden="true"></i>
                    <p>คู่มือการใช้งาน</p>
                </a>
            </li>

            @if (auth()->user()->hasAnyPermission(['customer_create', 'customer_view', 'customer_update', 'customer_delete', 'customer_sales', 'supplier_create', 'supplier_view', 'supplier_update', 'supplier_delete']))
                <li class="nav-item {{ request()->routeIs(['backend.admin.customers.index', 'backend.admin.customers.create', 'backend.admin.customers.edit', 'backend.admin.suppliers.index', 'backend.admin.suppliers.create', 'backend.admin.suppliers.edit']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-user-circle nav-icon" aria-hidden="true"></i>
                        <p>ลูกค้าและซัพพลายเออร์ <i class="fas fa-angle-left right" aria-hidden="true"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (auth()->user()->hasAnyPermission(['customer_create', 'customer_view', 'customer_update', 'customer_delete']))
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.customers.index') }}" class="nav-link {{ request()->routeIs(['backend.admin.customers.index', 'backend.admin.customers.edit', 'backend.admin.customers.create']) ? 'active' : '' }}">
                                    <i class="fas fa-circle nav-icon" aria-hidden="true"></i>
                                    <p>ลูกค้า</p>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasAnyPermission(['supplier_create', 'supplier_view', 'supplier_update', 'supplier_delete']))
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.suppliers.index') }}" class="nav-link {{ request()->routeIs(['backend.admin.suppliers.index', 'backend.admin.suppliers.edit', 'backend.admin.suppliers.create']) ? 'active' : '' }}">
                                    <i class="fas fa-circle nav-icon" aria-hidden="true"></i>
                                    <p>ซัพพลายเออร์</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if(auth()->user()->hasRole('Owner') || auth()->user()->hasRole('Admin'))
                <li class="nav-item">
                    <a href="{{ route('backend.admin.reward-rules.index') }}" class="nav-link {{ request()->routeIs(['backend.admin.reward-rules.*']) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-gift" aria-hidden="true"></i>
                        <p>จัดการสะสมแต้ม/รางวัล</p>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['product_create', 'product_view', 'product_update', 'product_delete', 'product_import', 'product_purchase']))
                <li class="nav-item {{ request()->routeIs(['backend.admin.products.index', 'backend.admin.products.create', 'backend.admin.products.edit', 'backend.admin.products.import', 'backend.admin.brands.index', 'backend.admin.brands.create', 'backend.admin.brands.edit', 'backend.admin.categories.index', 'backend.admin.categories.create', 'backend.admin.categories.edit', 'backend.admin.units.index', 'backend.admin.units.create', 'backend.admin.units.edit']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs(['backend.admin.products.index', 'backend.admin.products.create', 'backend.admin.products.edit', 'backend.admin.products.import', 'backend.admin.brands.index', 'backend.admin.brands.create', 'backend.admin.brands.edit', 'backend.admin.categories.index', 'backend.admin.categories.create', 'backend.admin.categories.edit', 'backend.admin.units.index', 'backend.admin.units.create', 'backend.admin.units.edit']) ? 'active' : '' }}">
                        <i class="fas fa-box nav-icon" aria-hidden="true"></i>
                        <p>สินค้า <i class="fas fa-angle-left right" aria-hidden="true"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (auth()->user()->hasAnyPermission(['product_view', 'product_update', 'product_delete']))
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.products.index') }}" class="nav-link {{ request()->routeIs(['backend.admin.products.index', 'backend.admin.products.edit']) ? 'active' : '' }}">
                                    <i class="fas fa-circle nav-icon" aria-hidden="true"></i>
                                    <p>รายการสินค้า</p>
                                </a>
                            </li>
                        @endif
                        @can('product_create')
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.products.create') }}" class="nav-link {{ request()->routeIs(['backend.admin.products.create']) ? 'active' : '' }}">
                                    <i class="fas fa-circle nav-icon" aria-hidden="true"></i>
                                    <p>เพิ่มสินค้า</p>
                                </a>
                            </li>
                        @endcan
                        @can('product_import')
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.products.import') }}" class="nav-link {{ request()->routeIs(['backend.admin.products.import']) ? 'active' : '' }}">
                                    <i class="fas fa-circle nav-icon" aria-hidden="true"></i>
                                    <p>นำเข้าสินค้า</p>
                                </a>
                            </li>
                        @endcan
                        @if (auth()->user()->hasAnyPermission(['brand_create', 'brand_view', 'brand_update', 'brand_delete']))
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.brands.index') }}" class="nav-link {{ request()->routeIs(['backend.admin.brands.index', 'backend.admin.brands.create', 'backend.admin.brands.edit']) ? 'active' : '' }}">
                                    <i class="fas fa-circle nav-icon" aria-hidden="true"></i>
                                    <p>แบรนด์/แหล่งสินค้า</p>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasAnyPermission(['category_create', 'category_view', 'category_update', 'category_delete']))
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.categories.index') }}" class="nav-link {{ request()->routeIs(['backend.admin.categories.index', 'backend.admin.categories.create', 'backend.admin.categories.edit']) ? 'active' : '' }}">
                                    <i class="fas fa-circle nav-icon" aria-hidden="true"></i>
                                    <p>หมวดสินค้า</p>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasAnyPermission(['unit_create', 'unit_view', 'unit_update', 'unit_delete']))
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.units.index') }}" class="nav-link {{ request()->routeIs(['backend.admin.units.index', 'backend.admin.units.create', 'backend.admin.units.edit']) ? 'active' : '' }}">
                                    <i class="fas fa-circle nav-icon" aria-hidden="true"></i>
                                    <p>หน่วยนับ</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @can('sale_view')
                <li class="nav-item {{ request()->routeIs(['backend.admin.orders.index']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs(['backend.admin.orders.index']) ? 'active' : '' }}">
                        <i class="fas fa-tags nav-icon" aria-hidden="true"></i>
                        <p>การขาย <i class="fas fa-angle-left right" aria-hidden="true"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.orders.index') }}" class="nav-link {{ request()->routeIs(['backend.admin.orders.index']) ? 'active' : '' }}">
                                <i class="fas fa-circle nav-icon" aria-hidden="true"></i>
                                <p>รายการขาย</p>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan

            @if (auth()->user()->hasAnyPermission(['purchase_create', 'purchase_view', 'purchase_update', 'purchase_delete']))
                <li class="nav-item {{ request()->routeIs(['backend.admin.purchase.index', 'backend.admin.purchase.create', 'backend.admin.purchase.edit']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs(['backend.admin.purchase.index', 'backend.admin.purchase.create', 'backend.admin.purchase.edit']) ? 'active' : '' }}">
                        <i class="fas fa-shopping-bag nav-icon" aria-hidden="true"></i>
                        <p>ซื้อเข้า <i class="fas fa-angle-left right" aria-hidden="true"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('purchase_view')
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.purchase.index') }}" class="nav-link {{ request()->routeIs(['backend.admin.purchase.index']) ? 'active' : '' }}">
                                    <i class="fas fa-circle nav-icon" aria-hidden="true"></i>
                                    <p>รายการซื้อเข้า</p>
                                </a>
                            </li>
                        @endcan
                        @can('purchase_create')
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.purchase.create') }}" class="nav-link {{ request()->routeIs(['backend.admin.purchase.create']) ? 'active' : '' }}">
                                    <i class="fas fa-circle nav-icon" aria-hidden="true"></i>
                                    <p>เพิ่มรายการซื้อเข้า</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['reports_summary', 'reports_sales', 'reports_inventory']))
                <li class="nav-item {{ request()->routeIs(['backend.admin.sale.report', 'backend.admin.sale.summery', 'backend.admin.inventory.report']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs(['backend.admin.sale.report', 'backend.admin.sale.summery', 'backend.admin.inventory.report']) ? 'active' : '' }}">
                        <i class="fas fa-chart-bar nav-icon" aria-hidden="true"></i>
                        <p>รายงาน <i class="fas fa-angle-left right" aria-hidden="true"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('reports_summary')
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.sale.summery') }}" class="nav-link {{ request()->routeIs(['backend.admin.sale.summery']) ? 'active' : '' }}">
                                    <i class="fas fa-circle nav-icon" aria-hidden="true"></i>
                                    <p>สรุปยอดขาย</p>
                                </a>
                            </li>
                        @endcan
                        @can('reports_sales')
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.sale.report') }}" class="nav-link {{ request()->routeIs(['backend.admin.sale.report']) ? 'active' : '' }}">
                                    <i class="fas fa-circle nav-icon" aria-hidden="true"></i>
                                    <p>รายงานขาย</p>
                                </a>
                            </li>
                        @endcan
                        @can('reports_inventory')
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.inventory.report') }}" class="nav-link {{ request()->routeIs(['backend.admin.inventory.report']) ? 'active' : '' }}">
                                    <i class="fas fa-circle nav-icon" aria-hidden="true"></i>
                                    <p>รายงานสต็อก</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['currency_create', 'currency_view', 'currency_update', 'currency_delete', 'currency_set_default', 'role_create', 'role_view', 'role_update', 'role_delete', 'permission_view', 'user_create', 'user_view', 'user_update', 'user_delete', 'user_suspend', 'website_settings', 'contact_settings', 'socials_settings', 'style_settings', 'custom_settings', 'notification_settings', 'website_status_settings', 'invoice_settings']))
                <li class="nav-header">ตั้งค่า</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog nav-icon" aria-hidden="true"></i>
                        <p>ตั้งค่าระบบ <i class="fas fa-angle-left right" aria-hidden="true"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (auth()->user()->hasAnyPermission(['website_settings', 'contact_settings', 'socials_settings', 'style_settings', 'custom_settings', 'notification_settings', 'website_status_settings', 'invoice_settings']))
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.settings.website.general') }}?active-tab=website-info" class="nav-link {{ $route === 'backend.admin.settings.website.general' ? 'active' : '' }}">
                                    <i class="fas fa-circle nav-icon" aria-hidden="true"></i>
                                    <p>ตั้งค่าทั่วไป</p>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasAnyPermission(['currency_create', 'currency_view', 'currency_update', 'currency_delete']))
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.currencies.index') }}" class="nav-link {{ request()->routeIs(['backend.admin.currencies.index', 'backend.admin.currencies.create', 'backend.admin.currencies.edit']) ? 'active' : '' }}">
                                    <i class="fas fa-coins nav-icon" aria-hidden="true"></i>
                                    <p>สกุลเงิน</p>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasAnyPermission(['role_create', 'role_view', 'role_update', 'role_delete', 'permission_view']))
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-chevron-circle-right nav-icon" aria-hidden="true"></i>
                                    <p>บทบาทและสิทธิ์ <i class="fas fa-angle-left right" aria-hidden="true"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('role_view')
                                        <li class="nav-item">
                                            <a href="{{ route('backend.admin.roles') }}" class="nav-link {{ $route === 'backend.admin.roles' ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon" aria-hidden="true"></i>
                                                <p>บทบาท</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('permission_view')
                                        <li class="nav-item">
                                            <a href="{{ route('backend.admin.permissions') }}" class="nav-link {{ $route === 'backend.admin.permissions' ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon" aria-hidden="true"></i>
                                                <p>สิทธิ์</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endif
                        @if (auth()->user()->hasAnyPermission(['user_create', 'user_view', 'user_update', 'user_delete', 'user_suspend']))
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.users') }}" class="nav-link {{ $route === 'backend.admin.users' ? 'active' : '' }}">
                                    <i class="fas fa-circle nav-icon" aria-hidden="true"></i>
                                    <p>ผู้ใช้งาน</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </nav>
</div>

<script>
    const treeviewElements = document.querySelectorAll('.nav-treeview');

    treeviewElements.forEach((treeviewElement) => {
        const navLinkElements = treeviewElement.querySelectorAll('.nav-link.active');

        if (navLinkElements.length > 0) {
            const parentNavItem = treeviewElement.closest('.nav-item');
            if (parentNavItem) {
                parentNavItem.classList.add('menu-open');
            }

            const childNavLink = parentNavItem.querySelector('.nav-link');
            if (childNavLink) {
                childNavLink.classList.add('active');
            }
        }
    });
</script>
