<html>
<link rel="stylesheet" href="{{ asset('public/fontend/css/category.css') }}">
<!-- AOS CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<body>


    <!-- ================ category section start ================= -->
    <section class="section-margin--small mb-5" data-aos="fade-up" data-aos-duration="800">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-5">
                    <div class="sidebar-categories" data-aos="fade-right" data-aos-duration="700">
                        <div class="head">Danh mục sản phẩm</div>
                        <form action="{{ route('category.index') }}" method="GET">
                            <select name="category" onchange="this.form.submit()">
                                @foreach ($productcategory as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                </div>
                <div class="col-xl-9 col-lg-8 col-md-7">
                    <!-- Start Filter Bar -->
                    <div class="filter-bar d-flex flex-wrap align-items-center" data-aos="fade-down"
                        data-aos-duration="700">

                        <div>
                            <div class="input-group filter-bar-search">
                                <input type="text" placeholder="Search">
                                <div class="input-group-append">
                                    <button type="button"><i class="ti-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Filter Bar -->
                    <!-- Start Best Seller -->
                    <section class="lattest-product-area pb-40 category-list">
                        <div class="row">
                            @foreach ($products as $product)
                                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-duration="800"
                                    data-aos-delay="{{ $loop->index * 100 }}">
                                    <div class="card text-center card-product">
                                        <div class="card-product__img" data-aos="zoom-in" data-aos-duration="600"
                                            data-aos-delay="{{ $loop->index * 80 }}">
                                            <img src="{{ asset('upload/products/' . $product->image) }}">
                                            <ul class="card-product__imgOverlay">
                                                <li>
                                                    <button
                                                        onclick="window.location.href='{{ route('productdetail.index', $product->id) }}'">
                                                        <i class="ti-search"></i>
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="add-to-cart" data-product-id="{{ $product->id }}">
                                                        <i class="ti-shopping-cart"></i>
                                                    </button>
                                                </li>
                                                <li>
                                                    <button><i class="ti-heart"></i></button>
                                                </li>
                                            </ul>

                                        </div>
                                        <div class="card-body" data-aos="fade-up" data-aos-duration="600"
                                            data-aos-delay="{{ $loop->index * 90 }}">
                                            {{ $product->productcategory->name ?? 'Danh mục không có' }}
                                            <h4 class="card-product__title">
                                                <h4 class="card-product__title">
                                                    <a
                                                        href="{{ route('productdetail.index', $product->id) }}">{{ $product->name }}</a>
                                                </h4>
                                                <p class="card-product__price">{{ $product->price }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                    <!-- End Best Seller -->
                </div>
            </div>
        </div>
    </section>
    <!-- ================ category section end ================= -->


    <!-- ================ Subscribe section start ================= -->
    <section class="subscribe-position" data-aos="fade-up" data-aos-duration="800">
        <div class="container">
            <div class="subscribe text-center" data-aos="fade-in" data-aos-duration="700">
                <h3 class="subscribe__title">Nhận thông báo mới nhất</h3>
                <div id="mc_embed_signup">
                    <form target="_blank"
                        action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01"
                        method="get" class="subscribe-form form-inline mt-5 pt-1">
                        <div class="form-group ml-sm-auto">
                            <input class="form-control mb-1" type="email" name="EMAIL" placeholder="Nhập email"
                                onfocus="this.placeholder = ''" onblur="this.placeholder = 'Your Email Address '">
                            <div class="info"></div>
                        </div>
                        <button class="button button-subscribe mr-auto mb-1" type="submit">Đăng kí ngay</button>
                        <div style="position: absolute; left: -5000px;">
                            <input name="b_36c4fd991d266f23781ded980_aefe40901a" tabindex="-1" value=""
                                type="text">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </section>
</body>

</html>

<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        once: true,
        easing: 'ease-in-out',
        duration: 800
    });

    document.addEventListener('DOMContentLoaded', async function() {
        const csrfToken = '{{ csrf_token() }}';
        const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        const loginUrl = '{{ route('login.index') }}';

        // Add to cart
        document.querySelectorAll('.add-to-cart').forEach(function(button) {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const quantity = 1;
                fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            products_id: productId,
                            quantity
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        document.querySelector('.nav-shop__circle').textContent = data
                            .cartCount;
                        Swal.fire({
                            icon: 'success',
                            title: 'Đã thêm vào giỏ hàng',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Không thể thêm sản phẩm vào giỏ hàng.'
                        });
                    });
            });
        });

        // Prompt login if unauthenticated
        async function requireAuthOrPrompt() {
            if (isAuthenticated) return true;
            const res = await Swal.fire({
                title: 'Bạn cần đăng nhập',
                text: 'Vui lòng đăng nhập để sử dụng chức năng yêu thích.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Đăng nhập',
                cancelButtonText: 'Hủy'
            });
            if (res.isConfirmed) window.location.href = loginUrl;
            return false;
        }

        // Favorite buttons
        const heartButtons = Array.from(document.querySelectorAll('.card-product__imgOverlay button'))
            .filter(btn => btn.querySelector('i.ti-heart'));

        function findProductId(btn) {
            const container = btn.closest('.card-product__img') || btn.closest('.card-product') || btn
                .closest('.col-md-6') || btn.closest('.col-lg-4');
            if (!container) return null;
            const any = container.querySelector('[data-product-id]');
            if (any) return parseInt(any.getAttribute('data-product-id'));
            const cartBtn = container.querySelector(
                '.add-to-cart[data-product-id], .add-to-cart_1[data-product-id]');
            if (cartBtn) return parseInt(cartBtn.getAttribute('data-product-id'));
            return null;
        }

        function setHeartVisual(btn, active) {
            const icon = btn.querySelector('i.ti-heart');
            if (!icon) return;
            if (active) {
                btn.classList.add('favorited');
                icon.style.color = '#e0245e';
            } else {
                btn.classList.remove('favorited');
                icon.style.color = '';
            }
        }

        // Load favorites: user from API, guest from localStorage
        let favorites = [];
        if (isAuthenticated) {
            try {
                const res = await fetch('{{ route('favorite.list') }}', {
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                if (res.ok) {
                    const json = await res.json();
                    favorites = json.favorites || [];
                }
            } catch (e) {
                console.error('Load favorites error', e);
            }
        } else {
            favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
        }

        // initial visuals
        heartButtons.forEach(btn => {
            const pid = findProductId(btn);
            if (pid && favorites.includes(pid)) setHeartVisual(btn, true);
        });

        // click handlers (check auth first)
        heartButtons.forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();
                if (!await requireAuthOrPrompt()) return;

                const pid = findProductId(this);
                if (!pid) {
                    console.debug('favorite: product id not found for button', this);
                    return;
                }

                try {
                    const res = await fetch('{{ route('favorite.toggle') }}', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            product_id: pid
                        })
                    });
                    const data = await res.json();
                    if (res.ok && data.success) {
                        const added = data.action === 'added';
                        setHeartVisual(this, added);
                        Swal.fire({
                            icon: 'success',
                            title: added ? 'Đã thêm vào yêu thích' :
                                'Đã xóa khỏi yêu thích',
                            timer: 1200,
                            showConfirmButton: false
                        });
                    } else {
                        throw new Error(data.message || 'Lỗi server');
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Không thể thay đổi yêu thích'
                    });
                }
            });
        });
    });
</script>

<style>
    .card-product__imgOverlay .favorited {
        background: rgba(224, 36, 94, 0.08);
    }

    .card-product__imgOverlay button {
        border: none;
        background: rgba(0, 0, 0, 0.04);
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
    }

    .card-product__imgOverlay button i.ti-heart {
        font-size: 16px;
        transition: color .15s ease;
    }
</style>
