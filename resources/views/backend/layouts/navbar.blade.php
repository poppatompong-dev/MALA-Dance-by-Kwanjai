<nav class="main-header navbar navbar-expand navbar-white navbar-light" aria-label="แถบนำทางหลังร้าน">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="เปิดหรือปิดเมนู">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        @can('sale_create')
            <li class="nav-item dropdown">
                <a class="nav-link btn bg-gradient-primary text-white" href="{{ route('backend.admin.cart.index') }}">
                    <i class="fas fa-cart-plus" aria-hidden="true"></i> ขายหน้าร้าน
                </a>
            </li>
        @endcan

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button" aria-label="เปิดเต็มหน้าจอ">
                <i class="fas fa-expand-arrows-alt" aria-hidden="true"></i>
            </a>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-label="เมนูผู้ใช้งาน">
                <i class="fas fa-user-circle" aria-hidden="true"></i>
                <i class="fas fa-angle-double-down" aria-hidden="true"></i>
            </a>
            <div class="dropdown-menu">
                <a href="{{ route('backend.admin.profile') }}" class="dropdown-item dropdown-footer">
                    <i class="fas fa-address-card" aria-hidden="true"></i>
                    โปรไฟล์
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item dropdown-footer">
                    <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                    ออกจากระบบ
                </a>
            </div>
        </li>
    </ul>
</nav>
