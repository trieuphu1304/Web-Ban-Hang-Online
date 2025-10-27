<body>


    <!--================Single Product Area =================-->
    <div class="product_image_area">
        <div class="container">
            <div class="row s_product_inner">
                <div class="col-lg-6">
                    <div class="owl-carousel owl-theme s_Product_carousel">
                        <div class="single-prd-item">
                            <img class="img-fluid" src="{{ asset('upload/products/' . $products->image) }}" alt="">
                        </div>
                        <div class="single-prd-item">
                            <img class="img-fluid" src="{{ asset('upload/products/' . $products->image) }}"
                                alt="">
                        </div>
                        <div class="single-prd-item">
                            <img class="img-fluid" src="{{ asset('upload/products/' . $products->image) }}"
                                alt="">
                        </div>
                        <div class="single-prd-item">
                            <img class="img-fluid" src="{{ asset('upload/products/' . $products->image) }}"
                                alt="">
                        </div>
                        <!-- <div class="single-prd-item>
       <img class="img-fluid" src="img/category/s-p1.jpg" alt="">
      </div>
      <div class="single-prd-item>
       <img class="img-fluid" src="img/category/s-p1.jpg" alt="">
      </div> -->
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <div class="s_product_text">
                        <h3>{{ $products->name }}</h3>
                        <h2>${{ $products->price }}</h2>
                        <ul class="list">
                            <li><a class="active" href="#"><span>Danh mục</span>:
                                    {{ $products->productcategory->name ?? 'No category' }}</a></li>
                            <li><a href="#"><span>Hiện tại</span>:
                                    {{ $products->stock ? 'Còn hàng' : 'Hết hàng' }}</a></li>
                        </ul>

                        <div class="product_count">
                            <input type="text" name="qty" id="sst" maxlength="12" value="1"
                                title="Quantity:" class="input-text qty">
                            <button
                                onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                                class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
                            <button
                                onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
                                class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
                        </div>
                        <a class="button primary-btn add-to-cart-btn" href="javascript:void(0)"
                            data-product-id="{{ $products->id }}">Thêm vào giỏ hàng</a>
                        <div class="card_area d-flex align-items-center">
                            <a class="icon_btn" href="javascript:void(0)"><i class="fas fa-gem"></i></a>
                            <!-- thêm data-product-id và class btn-favorite để JS nhận diện -->
                            <a class="icon_btn btn-favorite" href="javascript:void(0)"
                                data-product-id="{{ $products->id }}">
                                <i class="fas fa-heart"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--================End Single Product Area =================-->

    <!--================Product Description Area =================-->
    <section class="product_description_area">
        <div class="container">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="false">Mô tả</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                        aria-controls="profile" aria-selected="false">Thông tin sản phẩm</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active show" id="review-tab" data-toggle="tab" href="#review" role="tab"
                        aria-controls="review" aria-selected="true">Đánh giá</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <p>{{ $products->description }}</p>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <h5>Chiều rộng</h5>
                                    </td>
                                    <td>
                                        <h5>128mm</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5>Chiều cao</h5>
                                    </td>
                                    <td>
                                        <h5>508mm</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5>Chiều sâu</h5>
                                    </td>
                                    <td>
                                        <h5>85mm</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5>Cân nặng</h5>
                                    </td>
                                    <td>
                                        <h5>52gm</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5>Kiểm tra hàng</h5>
                                    </td>
                                    <td>
                                        <h5>Có</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5>Thời gian giao hàng</h5>
                                    </td>
                                    <td>
                                        <h5>03 ngày</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5>Mỗi hộp chứa</h5>
                                    </td>
                                    <td>
                                        <h5>60pcs</h5>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade active show" id="review" role="tabpanel" aria-labelledby="review-tab">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row total_rate">
                                <div class="col-6">
                                    <div class="box_total">
                                        <h5>Tổng thể</h5>
                                        <h4>4.0</h4>
                                        <h6>(03 Đánh giá)</h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rating_list">
                                        <h3>Dựa vào 3 bài đánh giá</h3>
                                        <ul class="list">
                                            <li><a href="#">5 Sao <i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i> 01</a></li>
                                            <li><a href="#">4 Sao <i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i> 01</a></li>
                                            <li><a href="#">3 Sao <i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i> 01</a></li>
                                            <li><a href="#">2 Sao <i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i> 01</a></li>
                                            <li><a href="#">1 Sao <i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i> 01</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="review_list">
                                <div class="review_item">
                                    <div class="media">
                                        <div class="d-flex">
                                            <img src="img/product/review-1.png" alt="">
                                        </div>
                                        <div class="media-body">
                                            <h4>Blake Ruiz</h4>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                        </div>
                                    </div>
                                    <p>Sản phẩm tuyệt vời</p>
                                </div>


                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="review_box">
                                <h4>Đánh giá sản phẩm</h4>
                                <p>Đánh giá của bạn:</p>
                                <ul class="list">
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                </ul>

                                <form action="#/" class="form-contact form-review mt-3">
                                    <div class="form-group">
                                        <input class="form-control" name="name" type="text" placeholder="Tên"
                                            required="">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" name="email" type="email"
                                            placeholder="Email" required="">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" name="subject" type="text"
                                            placeholder="Tiêu đề">
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control different-control w-100" name="textarea" id="textarea" cols="30" rows="5"
                                            placeholder="Nội dung"></textarea>
                                    </div>
                                    <div class="form-group text-center text-md-right mt-3">
                                        <button type="submit" class="button button--active button-review">Đánh giá
                                            ngay</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(function() {
            const csrfToken = '{{ csrf_token() }}';
            const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
            const loginUrl = '{{ url('/login') }}';

            // Add to cart (keeps existing behaviour)
            $('.add-to-cart-btn').off('click').on('click', function() {
                var productId = $(this).data('product-id');
                var quantity = parseInt($('#sst').val()) || 1;

                $.ajax({
                    url: "{{ route('cart.add') }}",
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        products_id: productId,
                        quantity: quantity
                    },
                    success: function(response) {
                        $('#cart-count').text(response.cartCount || 0);
                        Swal.fire({
                            icon: 'success',
                            title: 'Đã thêm sản phẩm vào giỏ!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        console.error('Add to cart error', xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Có lỗi xảy ra khi thêm sản phẩm!'
                        });
                    }
                });
            });

            // Helper: prompt login if not authenticated
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

            // Favorite handling (single consolidated handler for this page)
            const heartSelectors = '.icon_btn.btn-favorite, .card-product__imgOverlay button';
            const heartButtons = $(heartSelectors).filter(function() {
                return $(this).find('i.ti-heart, i.fas.fa-heart').length > 0;
            });

            function setHeartVisual($btn, active) {
                const $icon = $btn.find('i.ti-heart, i.fas.fa-heart').first();
                if (!$icon.length) return;
                $btn.toggleClass('favorited', !!active);
                if (active) $icon.css('color', '#e0245e');
                else $icon.css('color', '');
            }

            // If user is authenticated, try to load favorites from server (optional)
            let favorites = [];
            if (isAuthenticated) {
                fetch('{{ route('favorite.list') }}', {
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.ok ? r.json() : Promise.reject(r))
                    .then(json => {
                        favorites = json.favorites || [];
                        heartButtons.each(function() {
                            const pid = parseInt($(this).attr('data-product-id')) || parseInt($(this)
                                .closest('.card-product__img, .card-product').find(
                                    '[data-product-id]').attr('data-product-id'));
                            if (pid && favorites.includes(pid)) setHeartVisual($(this), true);
                        });
                    })
                    .catch(e => console.debug('No favorite.list or load failed', e));
            }

            // Attach click handler — require login first
            heartButtons.off('click').on('click', async function(e) {
                e.preventDefault();
                if (!await requireAuthOrPrompt()) return;

                const $btn = $(this);
                // find product id (priority: data-product-id on button, then inside container)
                let pid = parseInt($btn.attr('data-product-id'));
                if (!pid) {
                    pid = parseInt($btn.closest('.card-product__img, .card-product').find(
                        '[data-product-id]').attr('data-product-id'));
                }
                if (!pid) {
                    console.debug('favorite: product id not found for button', $btn);
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
                        setHeartVisual($btn, added);
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
                    console.error('Favorite toggle error', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Không thể thay đổi yêu thích'
                    });
                }
            });
        });
    </script>

    <style>
        .icon_btn.btn-favorite,
        .card-product__imgOverlay button {
            border: none;
            background: rgba(0, 0, 0, 0.04);
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            cursor: pointer;
        }

        .icon_btn.btn-favorite.favorited,
        .card-product__imgOverlay button.favorited {
            background: rgba(224, 36, 94, 0.08);
        }

        .icon_btn.btn-favorite i,
        .card-product__imgOverlay button i {
            transition: color .15s ease;
            font-size: 16px;
        }
    </style>
</body>
