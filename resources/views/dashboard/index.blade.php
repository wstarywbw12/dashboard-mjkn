@extends('layouts.app')

@section('title', 'Dashboard JKN Mobile | Monitoring Rawat Jalan & Rawat Inap')

@section('content')
    <!-- Filter Section -->
    <div class="filter-card mb-4">
        <div class="row align-items-end g-3">
            <div class="col-md-3">
                <label class="form-label"><i class="fas fa-chart-line me-1"></i> Tipe Layanan</label>
                <select class="form-select" id="serviceType">
                    <option value="rawatJalan">Rawat Jalan</option>
                    <option value="rawatInap">Rawat Inap</option>
                    <option value="total">Total Ranap & Rajal</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label"><i class="fas fa-calendar-alt me-1"></i> Periode</label>
                <select class="form-select" id="periodType">
                    <option value="daily">Per Hari Ini</option>
                    <option value="period">Per Periode</option>
                </select>
            </div>
            <div class="col-md-3" id="dateRangeContainer" style="display: none;">
                <label class="form-label"><i class="fas fa-calendar-week me-1"></i> Rentang Tanggal</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="dateRangePicker" placeholder="Pilih rentang tanggal">
                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <button class="btn btn-primary w-100" id="applyFilterBtn">
                    <i class="fas fa-filter me-2"></i> Terapkan Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Container untuk Grafik Rawat Jalan -->
    <div class="row" id="rawatJalanContainer">
        <div class="col-12">
            @include('dashboard.partials.chart-rawat-jalan')
        </div>
    </div>

    <!-- Container untuk Grafik Rawat Inap -->
    <div class="row" id="rawatInapContainer" style="display: none;">
        <div class="col-12">
            @include('dashboard.partials.chart-rawat-inap')
        </div>
    </div>

    <!-- Container untuk Grafik Total -->
    <div class="row" id="totalContainer" style="display: none;">
        <div class="col-12">
            @include('dashboard.partials.chart-total')
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Initialization
    let currentStartDate = moment().startOf('month');
    let currentEndDate = moment();
    let dateRangePicker;

    // Register Chart.js plugin
    Chart.register(ChartDataLabels);

    // Initialize Date Range Picker
    function initDateRangePicker() {
        dateRangePicker = $('#dateRangePicker').daterangepicker({
            startDate: currentStartDate,
            endDate: currentEndDate,
            locale: {
                format: 'DD/MM/YYYY',
                language: 'id'
            },
            ranges: {
                'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()]
            }
        }, (start, end) => {
            currentStartDate = start;
            currentEndDate = end;
        });
    }

    // Handle filter changes
    function applyFilters() {
        const serviceType = document.getElementById('serviceType').value;
        const periodType = document.getElementById('periodType').value;
        const dateRangeContainer = document.getElementById('dateRangeContainer');
        const rawatJalanContainer = document.getElementById('rawatJalanContainer');
        const rawatInapContainer = document.getElementById('rawatInapContainer');
        const totalContainer = document.getElementById('totalContainer');

        // Show/hide date range picker
        dateRangeContainer.style.display = periodType === 'period' ? 'block' : 'none';

        // Show/hide appropriate chart containers
        rawatJalanContainer.style.display = serviceType === 'rawatJalan' ? 'block' : 'none';
        rawatInapContainer.style.display = serviceType === 'rawatInap' ? 'block' : 'none';
        totalContainer.style.display = serviceType === 'total' ? 'block' : 'none';

        // Trigger data loading for visible charts
        if (serviceType === 'rawatJalan') {
            if (window.loadRawatJalanChart) {
                window.loadRawatJalanChart(periodType, periodType === 'period' ? currentStartDate : null, periodType === 'period' ? currentEndDate : null);
            }
        } else if (serviceType === 'rawatInap') {
            if (window.loadRawatInapChart) {
                window.loadRawatInapChart(periodType, periodType === 'period' ? currentStartDate : null, periodType === 'period' ? currentEndDate : null);
            }
        } else if (serviceType === 'total') {
            if (window.loadTotalCharts) {
                window.loadTotalCharts(periodType, periodType === 'period' ? currentStartDate : null, periodType === 'period' ? currentEndDate : null);
            }
        }
    }

    // Auto-refresh for daily data
    let autoRefreshInterval;

    function startAutoRefresh() {
        if (autoRefreshInterval) clearInterval(autoRefreshInterval);
        autoRefreshInterval = setInterval(() => {
            const periodType = document.getElementById('periodType').value;
            const serviceType = document.getElementById('serviceType').value;

            if (periodType === 'daily') {
                if (serviceType === 'rawatJalan' && window.loadRawatJalanChart) {
                    window.loadRawatJalanChart('daily');
                } else if (serviceType === 'rawatInap' && window.loadRawatInapChart) {
                    window.loadRawatInapChart('daily');
                } else if (serviceType === 'total' && window.loadTotalCharts) {
                    window.loadTotalCharts('daily');
                }

                // Animation effect on refresh button
                const badge = document.getElementById('refreshBtn');
                if (badge) {
                    badge.style.opacity = '0.7';
                    setTimeout(() => {
                        if (badge) badge.style.opacity = '1';
                    }, 200);
                }
            }
        }, 20000);
    }

    // Event listeners
    document.getElementById('applyFilterBtn').addEventListener('click', applyFilters);
    document.getElementById('serviceType').addEventListener('change', () => applyFilters());
    document.getElementById('periodType').addEventListener('change', () => applyFilters());
    document.getElementById('refreshBtn').addEventListener('click', () => {
        applyFilters();
        const icon = document.querySelector('#refreshBtn i');
        if (icon) {
            icon.style.transform = 'rotate(360deg)';
            setTimeout(() => {
                if (icon) icon.style.transform = '';
            }, 400);
        }
    });

    // Initial load
    initDateRangePicker();
    applyFilters();
    startAutoRefresh();
</script>
@endpush