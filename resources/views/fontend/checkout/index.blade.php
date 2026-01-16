<body>

    <section class="checkout_area section-margin--small">
        <div class="container">
            <div class="billing_details">
                <div class="row">
                    <div class="col-lg-12">
                        <form method="POST" action="{{ route('checkout') }}">
                            @csrf
                            <h3>Chi tiết hóa đơn</h3>

                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <input type="text" class="form-control" name="name" placeholder="Tên"
                                        value="{{ Auth::user()->name ?? '' }}" required>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <input type="text" class="form-control" name="address" placeholder="Địa chỉ"
                                        required>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <input type="text" class="form-control" name="phone"
                                        placeholder="Số điện thoại" required>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <input type="email" class="form-control" name="email" placeholder="Email"
                                        value="{{ Auth::user()->email ?? '' }}">
                                </div>
                            </div>

                            <ul class="list">
                                @foreach ($cart as $item)
                                    <li>
                                        <a href="#"
                                            style="display:flex; align-items:center; gap:12px; text-decoration:none; color:inherit;">
                                            <img src="{{ isset($item['image']) ? asset('upload/products/' . $item['image']) : asset('public/fontend/img/placeholder.png') }}"
                                                alt="{{ $item['name'] ?? 'Product' }}"
                                                style="width:56px; height:56px; object-fit:cover; border-radius:6px; flex-shrink:0;">
                                            <div style="display:flex; flex-direction:column;">
                                                <span
                                                    style="font-size:1rem; font-weight:600;">{{ $item['name'] }}</span>
                                                <small style="color:#666;">x {{ $item['quantity'] }}</small>
                                            </div>
                                            <span class="last" style="margin-left:auto; font-weight:600;">
                                                {{ number_format($item['price'], 0, ',', '.') }} $
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <ul class="list list_2">
                                <li><a href="#">Tổng mặt hàng <span>{{ number_format($total, 0, ',', '.') }}
                                            $</span></a></li>
                                <li><a href="#">Phí giao hàng <span>50 $</span></a></li>
                                <li><a href="#">Tổng <span>{{ number_format($total + 50, 0, ',', '.') }}
                                            $</span></a></li>
                            </ul>

                            <!-- chọn phương thức -->
                            <div class="payment_methods mb-4" style="display:flex; gap:20px;">
                                <label style="display:flex; align-items:center; gap:10px;">
                                    <input type="radio" name="payment_method" value="cod" checked>
                                    <img src="{{ asset('/fontend/tiền-icon.jpg') }}" width="36" height="36">
                                    <span>Tiền mặt (COD)</span>
                                </label>

                                <label style="display:flex; align-items:center; gap:10px;">
                                    <input type="radio" name="payment_method" value="momo">
                                    <img src="{{ asset('/fontend/momo.png') }}" width="36" height="36">
                                    <span>Thanh toán MoMo</span>
                                </label>
                            </div>

                            <div class="text-center">
                                @if (Auth::check())
                                    <button type="submit" class="button button-paypal">Đặt hàng</button>
                                @else
                                    <p>Vui lòng <a href="{{ route('login.index') }}">đăng nhập</a> để tiếp tục đặt
                                        hàng.</p>
                                    <a href="{{ route('login.index') }}" class="button button-login">Đăng nhập</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


</body>
