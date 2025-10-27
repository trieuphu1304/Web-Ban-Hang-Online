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
        const favListRoute = '{{ route('favorite.list') }}';
        const favToggleRoute = '{{ route('favorite.toggle') }}';

        // Guard: nếu script đã được gắn trước đó thì không gắn lại
        if (window.__favDelegated) return;
        window.__favDelegated = true;

        // helper prompt login
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
            const container = btn.closest('.card-product__img') || btn.closest('.card-product');
            if (!container) return null;
            const any = container.querySelector('[data-product-id]');
            return any ? parseInt(any.getAttribute('data-product-id')) : null;
        }

        function setHeartVisual(btn, active) {
            const icon = btn.querySelector('i.ti-heart');
            if (!icon) return;
            btn.classList.toggle('favorited', !!active);
            icon.style.color = active ? '#e0245e' : '';
        }

        // Load favorites (only once)
        let favorites = [];
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
                    favorites = json.favorites || [];
                }
            } else {
                favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            }
        } catch (e) {
            console.error('Load favorites error', e);
        }

        // Apply initial visuals (safe to run multiple times)
        document.querySelectorAll('.card-product__imgOverlay button').forEach(btn => {
            if (!btn.querySelector('i.ti-heart')) return;
            const pid = findProductId(btn);
            if (pid && favorites.includes(pid)) setHeartVisual(btn, true);
        });

        // Delegated click handler on nearest container (row)
        const rowContainer = document.querySelector('.row') || document;
        rowContainer.addEventListener('click', async function(e) {
            const btn = e.target.closest('button');
            if (!btn) return;
            if (!btn.querySelector('i.ti-heart')) return; // not a heart button

            e.preventDefault();

            // Prevent double processing on same button
            if (btn.dataset.favProcessing === '1') return;
            btn.dataset.favProcessing = '1';

            try {
                if (!await requireAuthOrPrompt()) {
                    btn.dataset.favProcessing = '0';
                    return;
                }

                const pid = findProductId(btn);
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
                if (res.ok && data.success) {
                    const added = data.action === 'added';
                    setHeartVisual(btn, added);
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
                console.error('Favorite toggle error', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Không thể thay đổi trạng thái yêu thích'
                });
            } finally {
                // unlock button
                btn.dataset.favProcessing = '0';
            }
        }, {
            passive: false
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
