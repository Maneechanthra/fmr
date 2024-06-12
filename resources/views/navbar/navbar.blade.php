<nav>
      <ul>
            <li>
                  <a href="{{ route('/') }}" class="logo">
                        <img src="{{ asset('assets/images/logo.png') }}" />
                        <span class="nav-item">FMRestaurant</span>
                  </a>
            </li>


            <li>
                  <a href="{{ route('report-info-user') }}">
                        <i class="fas fa-id-card"></i>
                        <span class="nav-item">รายงานสมาชิก</span>
                  </a>
            </li>
            <li>
                  <a href="{{ route('report-info-restaurant') }}">
                        <i class="fas fa-store"></i>
                        <span class="nav-item">รายงานข้อมูลร้านอาหาร</span>
                  </a>
            </li>
            <li>
                  <a href="{{ route('user-management') }}">
                        <i class="fas fa-user-cog"></i>
                        <span class="nav-item">จัดการข้อมูลสมาชิก</span>
                  </a>
            </li>
            <li>
                  <a href="{{ route('restaurant-management') }}">
                        <i class="fas fa-utensils"></i>
                        <span class="nav-item">จัดการข้อมูลร้านอาหาร</span>
                  </a>
            </li>

            <li>
                  <a href="{{ route('report-verify-restaurant') }}">
                        <i class="fas fa-envelope-open-text"></i>
                        <span class="nav-item">จัดการข้อมูลยืนยันตัวตน</span>
                  </a>
            </li>
            <li>
                  <a href="{{ route('report-restaurant-by-user') }}">
                        <i class="fas fa-flag"></i>
                        <span class="nav-item">รายงานความไม่เหมาะสม</span>
                  </a>
            </li>

            <li>
                  <a href="{{ route('admin-management') }}">
                        <i class="fas fa-user"></i>
                        <span class="nav-item">จัดการข้อมูลผู้ดูแลระบบ</span>
                  </a>
            </li>
            <li>
                  <a href="{{ route('update-personal') }}">
                        <i class="fas fa-user-edit"></i>
                        <span class="nav-item">แก้ไขข้อมูลส่วนตัว</span>
                  </a>
            </li>
            <li>
                  <form id="logout-form" action="{{ route('logout') }}" method="GET">
                        @csrf
                        <a href="{{ route('logout') }}" class="logout">
                              <i class="fas fa-sign-out-alt"></i>
                              <span class="nav-item">Log out</span>
                        </a>
                  </form>
            </li>
      </ul>
</nav>