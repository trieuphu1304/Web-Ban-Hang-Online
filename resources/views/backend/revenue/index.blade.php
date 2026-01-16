<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Trang chủ /</span> Thống kê doanh số</h4>

    <div class="col-12 col-lg-12 order-2 order-md-3 order-lg-2 mb-4">
        <div class="card">
            <div class="row row-bordered g-0">
                <div class="container mt-4">
                    <div class="card shadow-sm p-4">
                        <h2 class="text-center text-primary mb-4"> Thống kê doanh số</h2>

                        <!-- Form lọc dữ liệu -->
                        <form action="{{ route('revenue.index') }}" method="GET" class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="start_date" class="form-label"> Từ ngày:</label>
                                <input type="date" name="start_date" value="{{ $startDate }}"
                                    class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="end_date" class="form-label"> Đến ngày:</label>
                                <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100"> Lọc</button>
                            </div>
                        </form>

                        <!-- Biểu đồ thống kê doanh số -->
                        <div class="card p-3 mb-4">
                            <h4 class="text-center">Biểu đồ doanh số</h4>
                            <canvas id="revenueChart"></canvas>
                        </div>

                        <!-- Bảng thống kê doanh số -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th> Ngày</th>
                                        <th> Doanh số ($)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($revenues as $revenue)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($revenue->date)->format('d/m/Y') }}</td>
                                            <td class="fw-bold text-success">
                                                {{ number_format($revenue->revenue, 2, ',', '.') }} $ </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-muted">Không có dữ liệu doanh số trong khoảng
                                                thời gian này.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Thêm thư viện Chart.js -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        let labels = {!! json_encode($revenues->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!};
                        let data = {!! json_encode($revenues->pluck('revenue')) !!};

                        const ctx = document.getElementById('revenueChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Doanh số ($)',
                                    data: data,
                                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    });
                </script>

            </div>
        </div>
    </div>
</div>

</div>
