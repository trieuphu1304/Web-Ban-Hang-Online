<div class="container">
    <h2 class="fw-bold py-3 mb-4">Sản phẩm nổi bật</h2>
    <div class="row">
        @foreach ($trendingProducts as $products)
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
                            <li><button class="add-to-cart" data-product-id="{{ $products->id }}"><i
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

<script>
    document.addEventListener('DOMContentLoaded', async function() {
        const csrfToken = '{{ csrf_token() }}';
        const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        const loginUrl = '{{ route('login.index') }}';

        // Add to cart buttons
        document.querySelectorAll('.add-to-cart').forEach(function(button) {
            button.addEventListener('click', function() {
                var productId = this.getAttribute('data-product-id');
                var quantity = 1;
                fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            'products_id': productId,
                            'quantity': quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Đã thêm sản phẩm vào giỏ!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        const badge = document.querySelector('.nav-shop__circle');
                        if (badge) badge.textContent = data.cartCount ?? badge
                            .textContent;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Có lỗi xảy ra!',
                            text: 'Vui lòng thử lại sau.'
                        });
                    });
            });
        });

        // Helper: prompt login if unauthenticated
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

        // Favorite buttons (only the buttons that contain a ti-heart icon)
        const heartButtons = Array.from(document.querySelectorAll('.card-product__imgOverlay button'))
            .filter(btn => btn.querySelector('i.ti-heart'));

        // find product id inside the same product block
        function findProductId(btn) {
            const container = btn.closest('.card-product__img') || btn.closest('.card-product');
            if (!container) return null;
            const any = container.querySelector('[data-product-id]');
            if (any) return parseInt(any.getAttribute('data-product-id'));
            // fallback: check parent attributes
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

        // Load initial favorites (user from API, guest from localStorage)
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

        // Apply initial visuals
        heartButtons.forEach(btn => {
            const pid = findProductId(btn);
            if (pid && favorites.includes(pid)) setHeartVisual(btn, true);
        });

        // Click handlers (require login first)
        heartButtons.forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();

                // require login
                if (!await requireAuthOrPrompt()) return;

                const pid = findProductId(this);
                if (!pid) {
                    console.debug('favorite: product id not found for button', this);
                    return;
                }

                // Toggle favorite on server (authenticated user)
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
                        throw new Error(data.message || 'Lỗi');
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
