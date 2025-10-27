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
    document.addEventListener('DOMContentLoaded', async function() {
        const csrfToken = '{{ csrf_token() }}';
        const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        const loginUrl = '{{ route('login.index') }}';

        // Add to cart handler
        document.querySelectorAll('.add-to-cart_1').forEach(function(button) {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const quantity = 1;

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
                        document.querySelector('.nav-shop__circle').textContent = data
                            .cartCount;
                        Swal.fire({
                            icon: 'success',
                            title: 'Đã thêm vào giỏ hàng!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Không thể thêm vào giỏ hàng.'
                        });
                    });
            });
        });

        // Prompt login if not authenticated
        async function requireAuthOrPrompt() {
            if (isAuthenticated) return true;
            const result = await Swal.fire({
                title: 'Bạn cần đăng nhập',
                text: 'Vui lòng đăng nhập để sử dụng chức năng yêu thích.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Đăng nhập',
                cancelButtonText: 'Hủy'
            });
            if (result.isConfirmed) window.location.href = loginUrl;
            return false;
        }

        // Find product id from container
        function findProductId(btn) {
            const container = btn.closest('.card-product__img') || btn.closest('.card-product');
            if (!container) return null;
            const any = container.querySelector('[data-product-id]');
            return any ? parseInt(any.getAttribute('data-product-id')) : null;
        }

        // Set heart button visual state
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

        // Get all heart buttons
        const heartButtons = Array.from(document.querySelectorAll('.card-product__imgOverlay button'))
            .filter(btn => btn.querySelector('i.ti-heart'));

        // Load initial favorites state
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
                console.error('Load favorites error:', e);
            }
        } else {
            favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
        }

        // Apply initial states
        heartButtons.forEach(btn => {
            const pid = findProductId(btn);
            if (pid && favorites.includes(pid)) setHeartVisual(btn, true);
        });

        // Handle heart button clicks
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
                    console.error('Favorite toggle error:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Không thể thay đổi trạng thái yêu thích'
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
