<body>


    <section class="cart_area">
        <div class="container">
            <div class="cart_inner">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Sản phẩm</th>
                                <th scope="col">Giá</th>
                                <th scope="col">Thêm vào giỏ</th>
                                <th scope="col">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($favorites as $favorite)
                                <tr class="favorite-item" data-product-id="{{ $favorite->product->id }}">
                                    <td>
                                        <div class="media">
                                            <div class="d-flex">
                                                <img src="{{ asset('upload/products/' . $favorite->product->image) }}"
                                                    alt="{{ $favorite->product->name }}"
                                                    style="width: 100px; height: 100px; object-fit: cover;">
                                            </div>
                                            <div class="media-body">
                                                <a href="{{ route('productdetail.index', $favorite->product->id) }}">
                                                    <p>{{ $favorite->product->name }}</p>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5>{{ number_format($favorite->product->price, 2, ',', '.') }} VND</h5>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary add-to-cart"
                                            data-product-id="{{ $favorite->product->id }}">
                                            <i class="ti-shopping-cart"></i> Thêm vào giỏ
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger remove-favorite">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <h4>Chưa có sản phẩm yêu thích</h4>
                                        <a href="{{ route('shop.index') }}" class="btn btn-primary mt-3">
                                            Tiếp tục mua sắm
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = '{{ csrf_token() }}';

        // Xử lý thêm vào giỏ hàng
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            'products_id': productId,
                            'quantity': 1
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

        // Xử lý xóa khỏi yêu thích
        document.querySelectorAll('.remove-favorite').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('.favorite-item');
                const productId = row.getAttribute('data-product-id');

                Swal.fire({
                    title: 'Xác nhận xóa?',
                    text: "Bạn có chắc muốn bỏ sản phẩm này khỏi danh sách yêu thích?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route('favorite.toggle') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    product_id: productId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    row.remove();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Đã xóa khỏi danh sách yêu thích',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });

                                    // Nếu không còn sản phẩm nào, hiển thị message
                                    if (document.querySelectorAll('.favorite-item')
                                        .length === 0) {
                                        document.querySelector('tbody').innerHTML = `
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <h4>Chưa có sản phẩm yêu thích</h4>
                                                <a href="{{ route('shop.index') }}" class="btn btn-primary mt-3">
                                                    Tiếp tục mua sắm
                                                </a>
                                            </td>
                                        </tr>
                                    `;
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi!',
                                    text: 'Không thể xóa sản phẩm.'
                                });
                            });
                    }
                });
            });
        });
    });
</script>

<style>
    .favorite-item img {
        border-radius: 8px;
    }

    .favorite-item .media {
        align-items: center;
    }

    .favorite-item .media-body {
        padding-left: 20px;
    }

    .favorite-item .media-body p {
        margin: 0;
        font-size: 1.1rem;
        color: #222;
    }

    .favorite-item .btn {
        padding: 8px 16px;
    }

    .favorite-item .btn i {
        margin-right: 6px;
    }
</style>
