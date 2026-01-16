<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3">
                <h6>Tổng hàng đang có</h6>
                <h3>{{ number_format($totalStock) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <h6>Tổng số khách hàng</h6>
                <h3>{{ number_format($totalCustomers) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <h6>Tổng đơn đã giao</h6>
                <h3>{{ number_format($totalDelivered) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <h6>Tổng doanh thu</h6>
                <h3>{{ number_format($totalRevenue) }} $</h3>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card p-3">
                <h5>Doanh thu 30 ngày gần nhất</h5>
                <div id="sales-line-chart"></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h5>Top sản phẩm bán chạy</h5>
                <div id="top-products-bar"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var salesData = {!! json_encode($sales->pluck('total')) !!};
            var salesLabels = {!! json_encode($sales->pluck('date')) !!};

            var optionsLine = {
                chart: {
                    type: 'line',
                    height: 350
                },
                series: [{
                    name: 'Doanh thu',
                    data: salesData
                }],
                xaxis: {
                    categories: salesLabels
                },
                stroke: {
                    curve: 'smooth'
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return new Intl.NumberFormat().format(val) + ' $';
                        }
                    }
                }
            };
            var chartLine = new ApexCharts(document.querySelector('#sales-line-chart'), optionsLine);
            chartLine.render();

            var topNames = {!! json_encode($topProducts->pluck('name')) !!};
            var topVals = {!! json_encode($topProducts->pluck('total_sold')) !!};

            var optionsBar = {
                chart: {
                    type: 'bar',
                    height: 350
                },
                series: [{
                    name: 'Số lượng',
                    data: topVals
                }],
                xaxis: {
                    categories: topNames
                },
                plotOptions: {
                    bar: {
                        horizontal: false
                    }
                }
            };
            var chartBar = new ApexCharts(document.querySelector('#top-products-bar'), optionsBar);
            chartBar.render();
        });
    </script>
@endpush
