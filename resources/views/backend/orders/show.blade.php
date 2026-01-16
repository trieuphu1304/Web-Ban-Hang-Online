<div class="container-xxl flex-grow-1 container-p-y">
    <a href="{{ route('orders.index') }}" class="btn btn-secondary mb-3">← Quay lại</a>

    <h4 class="fw-bold py-3 mb-4">Chi tiết đơn hàng #{{ $order->id }}</h4>

    <div class="row mb-4">
        <div class="col-md-6">
            <h5>Thông tin khách hàng</h5>
            <p><strong>Tên:</strong> {{ $order->name }}</p>
            <p><strong>Email:</strong> {{ $order->email }}</p>
            @if (!empty($order->phone))
                <p><strong>Điện thoại:</strong> {{ $order->phone }}</p>
            @endif
            @if (!empty($order->address))
                <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
            @endif
            <p><strong>Ngày đặt:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</p>
            <p><strong>Trạng thái:</strong> {{ ucfirst($order->status) }}</p>
        </div>

    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Tên</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($order->items) && count($order->items))
                        @foreach ($order->items as $item)
                            <tr>
                                <td style="width:100px">
                                    @if (isset($item->product) && $item->product->image)
                                        <img src="{{ asset('upload/products/' . $item->product->image) }}"
                                            alt=""
                                            style="width:80px;height:80px;object-fit:cover;border-radius:6px;">
                                    @else
                                        <img src="{{ asset('fontend/img/placeholder.png') }}" alt=""
                                            style="width:80px;height:80px;object-fit:cover;border-radius:6px;">
                                    @endif
                                </td>
                                <td>{{ $item->product->name ?? ($item->product_name ?? '-') }}</td>
                                <td>{{ $item->quantity ?? 1 }}</td>
                                <td>{{ number_format($item->price ?? ($item->product->price ?? 0), 0, ',', '.') }} $
                                </td>
                                <td>{{ number_format(($item->price ?? ($item->product->price ?? 0)) * ($item->quantity ?? 1), 0, ',', '.') }}
                                    $</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">Không có sản phẩm nào trong đơn hàng</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
