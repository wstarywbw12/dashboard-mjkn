<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Dashboard JKN Mobile | Monitoring Rawat Jalan & Rawat Inap</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- Plugin untuk menampilkan data label di atas bar -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js">
    </script>
    <!-- Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <style>
        :root {
            --bg-dark: #0a0e1a;
            --card-dark: #111827;
            --border-dark: #1f2937;
            --text-primary: #f3f4f6;
            --text-secondary: #9ca3af;
            --accent-blue: #2563eb;
            --accent-orange: #f59e0b;
            --accent-purple: #7c3aed;
        }

        body {
            background-color: var(--bg-dark);
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            color: var(--text-primary);
        }

        .navbar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
            border-bottom: 1px solid var(--border-dark);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--text-primary) !important;
        }

        .card {
            background-color: var(--card-dark);
            border: 1px solid var(--border-dark);
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .card-header {
            background-color: rgba(59, 130, 246, 0.1);
            border-bottom: 1px solid var(--border-dark);
            border-radius: 1rem 1rem 0 0 !important;
            font-weight: 600;
        }

        .filter-card {
            background: var(--card-dark);
            border: 1px solid var(--border-dark);
            border-radius: 1rem;
            padding: 1.25rem;
        }

        .form-label {
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-select,
        .form-control,
        .input-group-text {
            background-color: #1e293b;
            border: 1px solid var(--border-dark);
            color: var(--text-primary);
        }

        .form-select:focus,
        .form-control:focus {
            background-color: #334155;
            border-color: var(--accent-blue);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }

        .form-select option {
            background-color: #1e293b;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-blue), #1d4ed8);
            border: none;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-1px);
        }

        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }

        canvas {
            max-height: 380px;
            width: 100% !important;
        }

        .badge-real-time {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            font-size: 0.7rem;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 0.6;
                transform: scale(0.95);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        footer {
            font-size: 0.8rem;
            border-top: 1px solid var(--border-dark);
            color: var(--text-secondary);
        }

        @media (max-width: 768px) {
            .chart-container {
                height: 320px;
            }

            .filter-card .row>div {
                margin-bottom: 0.75rem;
            }
        }

        /* Custom Date Range Picker Dark Theme */
        .daterangepicker {
            background-color: #1e293b;
            border-color: var(--border-dark);
        }

        .daterangepicker .calendar-table {
            background-color: #1e293b;
            border-color: var(--border-dark);
        }

        .daterangepicker td.off {
            background-color: #0f172a;
            color: #64748b;
        }

        .daterangepicker td.available:hover,
        .daterangepicker th.available:hover {
            background-color: #334155;
        }

        .daterangepicker td.active,
        .daterangepicker td.active:hover {
            background-color: var(--accent-blue);
        }

        .daterangepicker .ranges li:hover {
            background-color: #334155;
        }

        .daterangepicker .ranges li.active {
            background-color: var(--accent-blue);
        }

        .daterangepicker .calendar-table th,
        .daterangepicker .calendar-table td {
            color: var(--text-primary);
        }

        .daterangepicker .drp-buttons .btn {
            color: var(--text-primary);
        }

        /* Two Charts Layout */
        .two-charts {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 768px) {
            .two-charts {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark shadow-sm py-3">
        <div class="container-fluid px-4">
            <span class="navbar-brand">
                Dashboard Monitoring <strong>JKN Mobile</strong>
            </span>
            <div class="d-flex">
                <span class="badge bg-light text-dark me-2"><i class="far fa-clock me-1"></i> <span
                        id="realtimeClock"></span></span>
                <span class="badge badge-real-time" style="cursor: pointer;" id="refreshBtn">
                    <i class="fas fa-sync-alt me-1"></i> Refresh
                </span>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-3 px-md-4 py-4">

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
                        <input type="text" class="form-control" id="dateRangePicker"
                            placeholder="Pilih rentang tanggal">
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

        <!-- Single Chart Container (for rawatJalan or rawatInap) -->
        <div class="row" id="singleChartContainer">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center flex-wrap">
                        <span><i class="fas fa-stethoscope me-2 text-primary"></i>  <strong id="chartTitle">Per Hari Per
                                Poli (Real-time)</strong></span>
                        <span class="badge bg-info mt-1 mt-sm-0" id="chartSubtitle"><i class="fas fa-waveform"></i> Data
                            Real-time</span>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="dynamicChart"></canvas>
                        </div>
                        <div class="small-stat mt-3 text-center text-white" id="chartFooter">
                            <i class="fas fa-database"></i> Total pendaftaran JKN Mobile - Data real-time simulasi
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Two Charts Container (for total - Ranap & Rajal) -->
        <div class="row" id="twoChartsContainer" style="display: none;">
            <div class="col-12">
                <div class="two-charts">
                    <!-- Chart Rawat Jalan -->
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-transparent">
                            <span><i class="fas fa-stethoscope me-2 text-primary"></i> <strong>Rawat
                                    Jalan</strong></span>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="height: 380px;">
                                <canvas id="chartRajalTotal"></canvas>
                            </div>
                            <div class="small-stat mt-2 text-center text-white" id="footerRajal">
                                <i class="fas fa-database"></i> Total pendaftaran Rawat Jalan
                            </div>
                        </div>
                    </div>
                    <!-- Chart Rawat Inap -->
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-transparent">
                            <span><i class="fas fa-bed" style="color: #f59e0b;"></i> <strong>Rawat Inap</strong></span>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="height: 380px;">
                                <canvas id="chartRanapTotal"></canvas>
                            </div>
                            <div class="small-stat mt-2 text-center text-white" id="footerRanap">
                                <i class="fas fa-database"></i> Total pendaftaran Rawat Inap
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="text-center mt-4 pt-3 text-white">
            <i class="fas fa-mobile-alt"></i> Monitoring Terintegrasi dengan JKN Mobile
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Registrasi plugin datalabels
        Chart.register(ChartDataLabels);

        // Data definitions
        const poliList = ["Poli Umum", "Poli Anak", "Poli Bedah", "Poli Jantung", "Poli Saraf", "Poli Mata", "Poli THT"];
        const ruangInapList = ["Drupadi", "Gatot Kaca", "Yudistira", "Srikandi", "Abimanyu", "Sadewa", "Arimbi"];

        // TARGETS (tetap) - WARNA LEBIH PEKAT
        const TARGET_RAWAT_JALAN = 1600;
        const TARGET_RAWAT_INAP = 11200;

        // Data Periode Rawat Jalan (bulanan statis)
        const periodeRJData = {
            labels: ["Poli Bedah", "Poli Anak", "Poli Saraf", "Poli Mata", "Poli THT", "Poli Umum", "Poli Jantung"],
            data: [340, 310, 285, 280, 175, 165, 125]
        };

        // Data Periode Rawat Inap (bulanan statis)
        const periodeRIData = {
            labels: ["Yudistira", "Srikandi", "Gatot Kaca", "Drupadi", "Arimbi", "Abimanyu", "Sadewa"],
            data: [320, 295, 270, 245, 210, 185, 160]
        };

        let currentChart = null;
        let chartRajalTotal = null;
        let chartRanapTotal = null;

        // Fungsi generate data harian rawat jalan (real-time)
        function generateDailyRJSorted() {
            const rawData = poliList.map(() => Math.floor(Math.random() * 50) + 8);
            const combined = poliList.map((label, idx) => ({
                label,
                value: rawData[idx]
            }));
            combined.sort((a, b) => b.value - a.value);
            return {
                labels: combined.map(i => i.label),
                data: combined.map(i => i.value)
            };
        }

        // Fungsi generate data harian rawat inap (real-time)
        function generateDailyRISorted() {
            const rawData = ruangInapList.map(() => Math.floor(Math.random() * 30) + 5);
            const combined = ruangInapList.map((label, idx) => ({
                label,
                value: rawData[idx]
            }));
            combined.sort((a, b) => b.value - a.value);
            return {
                labels: combined.map(i => i.label),
                data: combined.map(i => i.value)
            };
        }

        // Fungsi generate data periode rawat jalan dengan filter tanggal
        function generatePeriodRJWithDateRange(startDate, endDate) {
            const multiplier = Math.floor((endDate - startDate) / (1000 * 60 * 60 * 24)) / 30;
            const faktor = Math.min(Math.max(multiplier, 0.5), 2);
            const rawData = periodeRJData.data.map(v => Math.floor(v * faktor));
            const combined = periodeRJData.labels.map((label, idx) => ({
                label,
                value: rawData[idx]
            }));
            combined.sort((a, b) => b.value - a.value);
            return {
                labels: combined.map(i => i.label),
                data: combined.map(i => i.value)
            };
        }

        // Fungsi generate data periode rawat inap dengan filter tanggal
        function generatePeriodRIWithDateRange(startDate, endDate) {
            const multiplier = Math.floor((endDate - startDate) / (1000 * 60 * 60 * 24)) / 30;
            const faktor = Math.min(Math.max(multiplier, 0.5), 2);
            const rawData = periodeRIData.data.map(v => Math.floor(v * faktor));
            const combined = periodeRIData.labels.map((label, idx) => ({
                label,
                value: rawData[idx]
            }));
            combined.sort((a, b) => b.value - a.value);
            return {
                labels: combined.map(i => i.label),
                data: combined.map(i => i.value)
            };
        }

        // Fungsi mendapatkan TOTAL keseluruhan Rawat Jalan
        function getTotalRawatJalan(periodType, startDate, endDate) {
            if (periodType === 'daily') {
                const result = generateDailyRJSorted();
                return result.data.reduce((a, b) => a + b, 0);
            } else {
                const result = generatePeriodRJWithDateRange(startDate, endDate);
                return result.data.reduce((a, b) => a + b, 0);
            }
        }

        // Fungsi mendapatkan TOTAL keseluruhan Rawat Inap
        function getTotalRawatInap(periodType, startDate, endDate) {
            if (periodType === 'daily') {
                const result = generateDailyRISorted();
                return result.data.reduce((a, b) => a + b, 0);
            } else {
                const result = generatePeriodRIWithDateRange(startDate, endDate);
                return result.data.reduce((a, b) => a + b, 0);
            }
        }

        // Warna chart - SOLID / PEKAT (tidak transparan)
        const chartColors = {
            rawatJalan: '#3b82f6',
            rawatInap: '#f59e0b',
            target: '#7c3aed'
        };

        const datalabelsConfig = {
            anchor: 'end',
            align: 'top',
            offset: 6,
            color: '#e2e8f0',
            fontWeight: 'bold',
            font: {
                size: 13,
                weight: 'bold'
            },
            formatter: (value) => value.toLocaleString('id-ID')
        };

        // Render Single Chart (Rawat Jalan atau Rawat Inap)
        function renderChart(serviceType, periodType, startDate = null, endDate = null) {
            let labels = [],
                data = [],
                chartLabel = '',
                backgroundColor = '',
                borderColor = '';
            let totalData = 0;

            if (serviceType === 'rawatJalan') {
                backgroundColor = chartColors.rawatJalan;
                borderColor = '#3b82f6';
                if (periodType === 'daily') {
                    const result = generateDailyRJSorted();
                    labels = result.labels;
                    data = result.data;
                    chartLabel = 'Jumlah Pendaftaran Hari Ini';
                    totalData = data.reduce((a, b) => a + b, 0);
                } else {
                    const result = generatePeriodRJWithDateRange(startDate, endDate);
                    labels = result.labels;
                    data = result.data;
                    chartLabel = 'Total Pendaftaran (Periode)';
                    totalData = data.reduce((a, b) => a + b, 0);
                }
            } else {
                backgroundColor = chartColors.rawatInap;
                borderColor = '#f59e0b';
                if (periodType === 'daily') {
                    const result = generateDailyRISorted();
                    labels = result.labels;
                    data = result.data;
                    chartLabel = 'Total Pendaftaran Rawat Inap Hari Ini';
                    totalData = data.reduce((a, b) => a + b, 0);
                } else {
                    const result = generatePeriodRIWithDateRange(startDate, endDate);
                    labels = result.labels;
                    data = result.data;
                    chartLabel = 'Total Pendaftaran Rawat Inap (Periode)';
                    totalData = data.reduce((a, b) => a + b, 0);
                }
            }

            const titleMap = {
                'rawatJalan_daily': 'Per Hari Per Poli (Real-time)',
                'rawatJalan_period': 'Per Periode - Rekapitulasi per Poli',
                'rawatInap_daily': 'Per Hari Per Ruang Rawat Inap (Real-time)',
                'rawatInap_period': 'Per Periode - Rekapitulasi per Ruang Rawat Inap'
            };
            const titleKey = `${serviceType}_${periodType}`;
            document.getElementById('chartTitle').innerHTML =
                `<i class="fas fa-chart-simple me-2"></i> ${titleMap[titleKey]}`;

            const subtitleText = periodType === 'daily' ? 'Data Real-time (update otomatis setiap 20 detik)' :
                (startDate && endDate ?
                    `Periode: ${moment(startDate).format('DD/MM/YYYY')} - ${moment(endDate).format('DD/MM/YYYY')}` :
                    'Periode: 01 April - 30 April 2025');
            document.getElementById('chartSubtitle').innerHTML = `<i class="fas fa-calendar-alt me-1"></i> ${subtitleText}`;
            document.getElementById('chartFooter').innerHTML =
                `<i class="fas fa-database"></i> Total ${totalData.toLocaleString('id-ID')} pendaftaran JKN Mobile`;

            if (currentChart) currentChart.destroy();
            const ctx = document.getElementById('dynamicChart').getContext('2d');
            currentChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: chartLabel,
                        data: data,
                        backgroundColor: backgroundColor,
                        borderColor: borderColor,
                        borderWidth: 2,
                        borderRadius: 8,
                        barPercentage: 0.65,
                        categoryPercentage: 0.8,
                        datalabels: datalabelsConfig
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 30,
                            bottom: 10,
                            left: 10,
                            right: 10
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#e2e8f0',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#111827',
                            titleColor: '#f3f4f6',
                            bodyColor: '#9ca3af',
                            callbacks: {
                                label: (ctx) => `${ctx.raw.toLocaleString('id-ID')} pendaftaran`
                            }
                        },
                        datalabels: datalabelsConfig
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#1f2937',
                                lineWidth: 1
                            },
                            title: {
                                display: true,
                                text: 'Jumlah Pendaftaran',
                                color: '#9ca3af'
                            },
                            ticks: {
                                color: '#9ca3af'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: serviceType === 'rawatJalan' ? 'Poli Rawat Jalan' : 'Ruang Rawat Inap',
                                color: '#9ca3af'
                            },
                            ticks: {
                                color: '#e2e8f0',
                                autoSkip: false,
                                maxRotation: 45,
                                minRotation: 45,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }

        function renderTotalCharts(periodType, startDate = null, endDate = null) {

            const totalRajal = Math.floor(TARGET_RAWAT_JALAN / 2);
            const totalRanap = Math.floor(TARGET_RAWAT_INAP / 2);

            document.getElementById('footerRajal').innerHTML =
                `<i class="fas fa-database"></i> Total ${totalRajal.toLocaleString('id-ID')} / Target ${TARGET_RAWAT_JALAN.toLocaleString('id-ID')}`;
            document.getElementById('footerRanap').innerHTML =
                `<i class="fas fa-database"></i> Total ${totalRanap.toLocaleString('id-ID')} / Target ${TARGET_RAWAT_INAP.toLocaleString('id-ID')}`;

            // =====================================================
            // PLUGIN: Gambar batang TOTAL manual di atas TARGET
            // Posisi dijamin tepat tengah karena pakai koordinat
            // pixel langsung dari batang Target yang sudah ada
            // =====================================================
            const overlayPlugin = {
                id: 'overlayPlugin',
                afterDatasetsDraw(chart) {
                    const opts = chart.config.options.plugins.overlayBar;
                    if (!opts) return;

                    const {
                        ctx
                    } = chart;
                    const meta = chart.getDatasetMeta(0);
                    const targetBar = meta.data[0];

                    if (!targetBar) return;

                    // Ambil posisi & ukuran batang Target
                    const cx = targetBar.x; // center X batang target
                    const yBottom = targetBar.base; // dasar batang
                    const yScale = chart.scales.y;
                    const yTop = yScale.getPixelForValue(opts.value); // puncak batang total
                    const barH = yBottom - yTop;

                    if (barH <= 0) return;

                    // Lebar batang Total = 50% dari lebar batang Target
                    const w = targetBar.width * 0.50;
                    const x = cx - w / 2;
                    const r = Math.min(12, w / 2, barH / 2);

                    ctx.save();

                    // Gambar rounded rect (hanya sudut atas yang rounded)
                    ctx.beginPath();
                    ctx.moveTo(x + r, yTop);
                    ctx.lineTo(x + w - r, yTop);
                    ctx.quadraticCurveTo(x + w, yTop, x + w, yTop + r);
                    ctx.lineTo(x + w, yBottom);
                    ctx.lineTo(x, yBottom);
                    ctx.lineTo(x, yTop + r);
                    ctx.quadraticCurveTo(x, yTop, x + r, yTop);
                    ctx.closePath();

                    ctx.fillStyle = opts.color;
                    ctx.fill();
                    ctx.strokeStyle = opts.borderColor;
                    ctx.lineWidth = 2;
                    ctx.stroke();

                    // Label angka di atas batang Total
                    ctx.fillStyle = '#ffffff';
                    ctx.font = 'bold 17px sans-serif';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';
                    ctx.fillText(opts.value.toLocaleString('id-ID'), cx, yTop - 6);

                    ctx.restore();
                }
            };

            // =====================================================
            // HELPER BUILD CONFIG
            // =====================================================
            function buildConfig(targetValue, totalValue, totalColor, totalBorderColor, yMax, yTitle) {
                return {
                    type: 'bar',
                    plugins: [overlayPlugin],
                    data: {
                        labels: [''],
                        datasets: [
                            // HANYA 1 DATASET = TARGET
                            // Batang Total digambar manual oleh plugin
                            {
                                label: 'Target',
                                data: [targetValue],
                                backgroundColor: '#2b004a', // ungu tua (solid)
                                borderColor: '#930ff2', // ungu muda (border)
                                borderWidth: 2,
                                borderRadius: 12,
                                barPercentage: 0.55,
                                categoryPercentage: 0.9,
                                datalabels: {
                                    anchor: 'end',
                                    align: 'top',
                                    offset: 6,
                                    color: '#ffffff',
                                    font: {
                                        size: 15,
                                        weight: 'bold'
                                    },
                                    formatter: (v) => v.toLocaleString('id-ID')
                                }
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                top: 60,
                                bottom: 10
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#e2e8f0',
                                    boxWidth: 14,
                                    padding: 20,
                                    usePointStyle: true,
                                    pointStyle: 'rect',
                                    // Legend manual karena Total bukan dataset resmi
                                    generateLabels: () => [{
                                            text: 'Target',
                                            fillStyle: '#2b004a',
                                            strokeStyle: '#930ff2',
                                            lineWidth: 2,
                                            pointStyle: 'rect',
                                            fontColor: '#ffffff', // ← tambah ini
                                            color: '#ffffff'
                                        },
                                        {
                                            text: 'Total',
                                            fillStyle: totalColor,
                                            strokeStyle: totalBorderColor,
                                            lineWidth: 2,
                                            pointStyle: 'rect'
                                        }
                                    ]
                                }
                            },
                            tooltip: {
                                backgroundColor: '#111827',
                                callbacks: {
                                    label: (ctx) => `${ctx.dataset.label}: ${ctx.raw.toLocaleString('id-ID')}`
                                }
                            },
                            // Config untuk overlayPlugin
                            overlayBar: {
                                value: totalValue,
                                color: totalColor,
                                borderColor: totalBorderColor
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    display: false
                                },
                                offset: true
                            },
                            y: {
                                beginAtZero: true,
                                max: yMax,
                                grid: {
                                    color: 'rgba(255,255,255,0.08)'
                                },
                                ticks: {
                                    color: '#cbd5e1',
                                    font: {
                                        size: 13
                                    }
                                },
                                title: {
                                    display: true,
                                    text: yTitle,
                                    color: '#cbd5e1'
                                }
                            }
                        }
                    }
                };
            }

            // =====================================================
            // CHART RAWAT JALAN
            // =====================================================
            if (chartRajalTotal) chartRajalTotal.destroy();
            chartRajalTotal = new Chart(
                document.getElementById('chartRajalTotal').getContext('2d'),
                buildConfig(
                    TARGET_RAWAT_JALAN, totalRajal,
                    '#3b82f6', '#60a5fa',
                    TARGET_RAWAT_JALAN + 300,
                    'Jumlah Pendaftaran'
                )
            );

            // =====================================================
            // CHART RAWAT INAP
            // =====================================================
            if (chartRanapTotal) chartRanapTotal.destroy();
            chartRanapTotal = new Chart(
                document.getElementById('chartRanapTotal').getContext('2d'),
                buildConfig(
                    TARGET_RAWAT_INAP, totalRanap,
                    '#f59e0b', '#fbbf24',
                    TARGET_RAWAT_INAP + 2000,
                    'Jumlah Pendaftaran'
                )
            );
        }

        // Date Range Picker initialization
        let dateRangePicker;
        let currentStartDate = moment().startOf('month');
        let currentEndDate = moment();

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
                    'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                        .endOf('month')
                    ],
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
            const singleContainer = document.getElementById('singleChartContainer');
            const twoContainer = document.getElementById('twoChartsContainer');

            if (serviceType === 'total') {
                // Tampilkan dua grafik
                singleContainer.style.display = 'none';
                twoContainer.style.display = 'block';
                dateRangeContainer.style.display = periodType === 'period' ? 'block' : 'none';

                if (periodType === 'period') {
                    if (!dateRangePicker) initDateRangePicker();
                    renderTotalCharts(periodType, currentStartDate, currentEndDate);
                } else {
                    renderTotalCharts(periodType);
                }
            } else {
                // Tampilkan satu grafik biasa
                singleContainer.style.display = 'block';
                twoContainer.style.display = 'none';

                if (periodType === 'period') {
                    dateRangeContainer.style.display = 'block';
                    if (!dateRangePicker) initDateRangePicker();
                    renderChart(serviceType, periodType, currentStartDate, currentEndDate);
                } else {
                    dateRangeContainer.style.display = 'none';
                    renderChart(serviceType, periodType);
                }
            }
        }

        // Auto-refresh for daily data only
        let autoRefreshInterval;

        function startAutoRefresh() {
            if (autoRefreshInterval) clearInterval(autoRefreshInterval);
            autoRefreshInterval = setInterval(() => {
                const periodType = document.getElementById('periodType').value;
                const serviceType = document.getElementById('serviceType').value;

                if (periodType === 'daily') {
                    if (serviceType === 'total') {
                        renderTotalCharts('daily');
                    } else {
                        renderChart(serviceType, 'daily');
                    }
                    updateClock();

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

        function updateClock() {
            const now = new Date();
            const formatted = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const clockSpan = document.getElementById('realtimeClock');
            if (clockSpan) clockSpan.innerText = formatted;
        }

        // Event Listeners
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

        // Window resize handler
        window.addEventListener('resize', () => {
            if (currentChart) currentChart.resize();
            if (chartRajalTotal) chartRajalTotal.resize();
            if (chartRanapTotal) chartRanapTotal.resize();
        });

        // Initial load
        initDateRangePicker();
        applyFilters();
        startAutoRefresh();
        setInterval(updateClock, 1000);
        updateClock();

        console.log("Dashboard Modern Dark Theme - Filter dengan Total Ranap & Rajal siap (Warna solid/pekat)");
    </script>
</body>

</html>
