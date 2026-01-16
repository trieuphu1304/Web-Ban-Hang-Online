<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Trang chủ /</span> Sản phẩm</h4>

    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <div class="content d-flex justify-content-end gap-2">
                    <a href="{{ route('products.create') }}" class="btn btn-success">
                        <i class="fa fa-plus"></i> Thêm sản phẩm
                    </a>
                </div>
                <tr>
                    <th><input type="checkbox" value="" id="checkAll" class="input-checkbox"></th>
                    <th>Tên sản phẩm</th>
                    <th>Tên danh mục sản phẩm</th>
                    <th>Giá ($)</th>
                    <th>Hình ảnh</th>
                    <th>Số lượng</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @if (isset($products) && is_object($products))
                    @foreach ($products as $product)
                        <tr>
                            <td><input type="checkbox" value="{{ $product->id }}" class="input-checkbox checked-item">
                            </td>
                            <td>
                                {{ $product->name }}
                            </td>

                            <td>
                                @if ($product->productcategory)
                                    {{ $product->productcategory->name }}
                                @else
                                    <span class="text-muted">Danh mục không có</span>
                                @endif
                            </td>
                            <td>
                                {{ $product->price }}
                            </td>

                            <td>
                                @if ($product->image)
                                    <img src="{{ asset('upload/products/' . $product->image) }}" alt="Image"
                                        style="width: 100px;">
                                @endif
                            </td>
                            <td>
                                {{ $product->quantity }}
                            </td>
                            <td>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-success"><i
                                        class="bx bx-edit-alt me-1"></i></a>
                                <form method="POST" action="{{ route('products.delete', $product->id) }}"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                        <i class="bx bx-trash me-1"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

    </div>
    <div class="pagination">
        {{ $products->links('pagination::bootstrap-4') }}
    </div>
</div>
