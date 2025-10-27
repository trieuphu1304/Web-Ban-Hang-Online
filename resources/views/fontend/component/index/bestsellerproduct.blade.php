<section class="section-margin calc-60px">
    <div class="container">
        <div class="section-intro pb-60px">
            <p>Sản phẩm thịnh hành</p>
            <h2>Sản phẩm <span class="section-intro__style">bán chạy</span></h2>
        </div>
        <div class="row">
            @foreach ($bestSellerProducts as $products)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card text-center card-product">
                        <!-- Hình ảnh sản phẩm -->
                        <div class="card-product__img">
                            <img class="img-fluid" src="{{ asset('upload/products/' . $products->image) }}"
                                alt="{{ $products->name }}">
                            <ul class="card-product__imgOverlay">
                                <li>
                                    <button
                                        onclick="window.location.href='{{ route('productdetail.index', $products->id) }}'">
                                        <i class="ti-search"></i>
                                    </button>
                                </li>
                                <li><button class="add-to-cart_1" data-product-id="{{ $products->id }}"><i
                                            class="ti-shopping-cart"></i></button></li>
                                <li><button><i class="ti-heart"></i></button></li>
                            </ul>
                        </div>
                        <!-- Nội dung sản phẩm -->
                        <div class="card-body">
                            <h4 class="card-product__title"><a
                                    href="{{ route('productdetail.index', ['id' => $products->id]) }}">{{ $products->name }}</a>
                            </h4>
                            <p class="card-product__price">${{ number_format($products->price, 2) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = '{{ csrf_token() }}';
        const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        const loginUrl = '{{ route('login.index') }}';
        const favListRoute = '{{ route('favorite.list') }}';
        const favToggleRoute = '{{ route('favorite.toggle') }}';
        const cartAddRoute = '{{ route('cart.add') }}';

        // guard để không gắn nhiều lần
        if (window.__favHandlersInit) {
            console.debug('fav handlers already initialized');
            return;
        }
        window.__favHandlersInit = true;
        console.debug('fav handlers init');

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

        function findProductId(btn) {
            if (!btn) return null;
            const container = btn.closest('.card-product__img') || btn.closest('.card-product') || btn.closest(
                'div.card');
            if (!container) return null;
            const any = container.querySelector('[data-product-id]');
            if (any) return parseInt(any.getAttribute('data-product-id')) || null;
            // tìm trong chính button (nếu có)
            const own = btn.getAttribute('data-product-id');
            return own ? parseInt(own) : null;
        }

        function setHeartVisual(btn, active) {
            const icon = btn.querySelector('i.ti-heart') || btn.querySelector('i.fas.fa-heart');
            if (!icon) return;
            btn.classList.toggle('favorited', !!active);
            icon.style.color = active ? '#e0245e' : '';
        }

        // load favorites once (optional)
        (async function loadFavorites() {
            try {
                if (isAuthenticated) {
                    const res = await fetch(favListRoute, {
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (res.ok) {
                        const json = await res.json();
                        window.__favorites = json.favorites || [];
                    } else {
                        window.__favorites = [];
                    }
                } else {
                    window.__favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
                }
            } catch (e) {
                console.error('Load favorites error', e);
                window.__favorites = [];
            }

            // apply visuals
            document.querySelectorAll('.card-product__imgOverlay button').forEach(btn => {
                if (!btn.querySelector('i.ti-heart')) return;
                const pid = findProductId(btn);
                if (pid && Array.isArray(window.__favorites) && window.__favorites.includes(
                    pid)) setHeartVisual(btn, true);
            });
        })();

        // Delegation: attach single listener on document
        document.addEventListener('click', async function(e) {
            const btn = e.target.closest('button');
            if (!btn) return;

            // add-to-cart_1
            if (btn.classList.contains('add-to-cart_1')) {
                e.preventDefault();
                if (btn.dataset.cartProcessing === '1') return;
                btn.dataset.cartProcessing = '1';
                const productId = btn.getAttribute('data-product-id');
                console.debug('add-to-cart click', productId);
                try {
                    const res = await fetch(cartAddRoute, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            products_id: productId,
                            quantity: 1
                        })
                    });
                    const data = await res.json();
                    console.debug('add-to-cart response', data);
                    document.querySelector('.nav-shop__circle') && (document.querySelector(
                            '.nav-shop__circle').textContent = data.cartCount ?? document
                        .querySelector('.nav-shop__circle').textContent);
                    Swal.fire({
                        icon: 'success',
                        title: 'Đã thêm vào giỏ hàng!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } catch (err) {
                    console.error('Add to cart error', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Không thể thêm vào giỏ hàng.'
                    });
                } finally {
                    btn.dataset.cartProcessing = '0';
                }
                return;
            }

            // favorite heart
            if (!btn.querySelector('i.ti-heart')) return;
            e.preventDefault();

            console.debug('heart clicked', btn);
            if (btn.dataset.favProcessing === '1') {
                console.debug('fav processing locked for this button');
                return;
            }
            btn.dataset.favProcessing = '1';

            try {
                if (!await requireAuthOrPrompt()) {
                    btn.dataset.favProcessing = '0';
                    return;
                }

                const pid = findProductId(btn);
                console.debug('favorite pid', pid);
                if (!pid) {
                    console.debug('favorite: product id not found for button', btn);
                    btn.dataset.favProcessing = '0';
                    return;
                }

                const res = await fetch(favToggleRoute, {
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
                console.debug('fav toggle response', data);
                if (res.ok && data.success) {
                    const added = data.action === 'added';
                    setHeartVisual(btn, added);
                    // update cached favorites
                    if (!Array.isArray(window.__favorites)) window.__favorites = [];
                    window.__favorites = added ? Array.from(new Set([...window.__favorites, pid])) :
                        window.__favorites.filter(x => x !== pid);
                    Swal.fire({
                        icon: 'success',
                        title: added ? 'Đã thêm vào yêu thích' : 'Đã xóa khỏi yêu thích',
                        timer: 1200,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message || 'Lỗi server');
                }
            } catch (err) {
                console.error('Favorite toggle error:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Không thể thay đổi trạng thái yêu thích'
                });
            } finally {
                btn.dataset.favProcessing = '0';
            }
        }, true); // use capture true for earlier handling
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
        justify-content: center;
        border-radius: 6px;
    }

    card-product__imgOverlay button i.ti-heart {
        font-size: 16px;

        .card-product__imgOverlay button i.ti-heart {
            font-size: 16px;
            transition: color .15s ease;
        }
</style>
