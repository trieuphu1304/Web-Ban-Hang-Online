<header class="header_area">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand logo_h" href="{{ route('shop.index') }}">
                    <img src="{{ asset('/fontend/img/logo.png') }}" alt="Logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                    <ul class="nav navbar-nav menu_nav ml-auto mr-auto">
                        <li class="nav-item">
                            <a href="{{ route('shop.index') }}" class="nav-link">Trang chủ</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('category.index') }}" class="nav-link">Danh mục</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('viewblog.index') }}" class="nav-link">Tin tức</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('contact.index') }}" class="nav-link">Liên hệ</a>
                        </li>

                    </ul>
                    @php
                        $totalQuantity = 0;
                    @endphp

                    @if (session('cart'))
                        @foreach (session('cart') as $item)
                            @php
                                $totalQuantity += $item['quantity'];
                            @endphp
                        @endforeach
                    @endif

                    <ul class="nav-shop">
                        <li class="nav-item"><button><i class="ti-search"></i></button></li>
                        <li class="nav-item">
                            <a href="{{ route('cart.index') }}" class="btn">
                                <i class="ti-shopping-cart"></i>
                                <span class="nav-shop__circle" id="cart-count">{{ $totalQuantity }}</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav-shop">
                        <li class="nav-item submenu dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fa-regular fa-user"></i>
                                @auth
                                    {{ Auth::user()->name }}
                                @endauth
                            </a>
                            <ul class="dropdown-menu">
                                @guest
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login.index') }}">Đăng nhập</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register.index') }}">Đăng ký</a>
                                    </li>
                                @endguest
                                @auth
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('authfontend.logout') }}">Đăng xuất</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('favorite.index') }}">Yêu thích</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('profile.index') }}">
                                            Tài khoản
                                        </a>
                                    </li>
                                @endauth
                            </ul>
                            <style>
                                /* Cải thiện kiểu dáng của menu dropdown */
                                .dropdown-menu {
                                    background-color: #ffffff;
                                    /* Màu nền trắng */
                                    border-radius: 8px;
                                    /* Bo tròn góc */
                                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                    /* Tạo hiệu ứng bóng đổ */
                                    padding: 10px 20px;
                                    /* Thêm padding để các mục không bị sát nhau */
                                    min-width: 135px;
                                    /* Đảm bảo dropdown đủ rộng */
                                }

                                /* Cải thiện giao diện các item trong menu */
                                .dropdown-menu .nav-item {
                                    margin-bottom: 8px;
                                    /* Khoảng cách giữa các mục */
                                }

                                /* Định dạng liên kết trong menu */
                                .dropdown-menu .nav-link {
                                    color: #333;
                                    /* Màu chữ xám tối */
                                    font-size: 16px;
                                    /* Kích thước chữ phù hợp */
                                    text-decoration: none;
                                    /* Loại bỏ gạch chân */
                                    display: block;
                                    /* Đảm bảo mục chiếm toàn bộ chiều rộng */
                                    padding: 8px 0;
                                    /* Padding cho các mục */
                                    border-radius: 6px;
                                    /* Bo tròn góc các mục */
                                    transition: background-color 0.3s ease, color 0.3s ease;
                                    /* Thêm hiệu ứng chuyển màu */
                                }

                                /* Hiệu ứng hover cho các item */
                                .dropdown-menu .nav-link:hover {
                                    background-color: #007bff;
                                    /* Màu nền khi hover */
                                    color: #fff;
                                    /* Màu chữ khi hover */
                                }

                                /* Style cho các mục đã được chọn hoặc đang hover */
                                .dropdown-menu .nav-link:focus,
                                .dropdown-menu .nav-link:active {
                                    background-color: #0056b3;
                                    /* Màu nền khi focus hoặc active */
                                    color: #fff;
                                    /* Màu chữ khi focus hoặc active */
                                }

                                /* Tùy chỉnh cho các mục không có margin bên trái */
                                .dropdown-menu .nav-item:last-child {
                                    margin-left: 0px;
                                }

                                /* Thêm một chút padding cho đầu menu */
                                .dropdown-menu .nav-item:first-child {
                                    margin-top: 0px;
                                }
                            </style>

                        </li>
                    </ul>

                </div>
            </div>
        </nav>
    </div>
</header>
