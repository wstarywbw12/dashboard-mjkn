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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 1px;
        }

        .card-header {
            font-weight: 600;
            background-color: rgba(13, 110, 253, 0.05);
            border-bottom: 2px solid rgba(13, 110, 253, 0.2);
        }

        .badge-real-time {
            background-color: #d9534f;
            font-size: 0.7rem;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 0.6;
            }

            100% {
                opacity: 1;
            }
        }

        .chart-container {
            position: relative;
            height: 380px;
            width: 100%;
        }

        canvas {
            max-height: 360px;
            width: 100% !important;
        }

        footer {
            font-size: 0.8rem;
            border-top: 1px solid #dee2e6;
        }

        @media (max-width: 768px) {
            .chart-container {
                height: 320px;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <span class="navbar-brand">
                <i class="fas fa-hospital-user me-2"></i>
                Dashboard Monitoring <strong>JKN Mobile</strong>
            </span>
            <div class="d-flex">
                <span class="badge bg-light text-dark me-2"><i class="far fa-clock me-1"></i> <span
                        id="realtimeClock"></span></span>
                <span class="badge bg-danger badge-real-time" style="cursor: pointer;" id="refreshBtn">
                    <i class="fas fa-sync-alt me-1"></i> Real-time
                </span>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-3 px-md-4 py-4">

        <!-- SECTION 1: RAWAT JALAN -->
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="fw-bold text-primary mb-0"><i class="fas fa-stethoscope me-2"></i>Rawat Jalan
                </h3>
                <hr class="mt-2 mb-3">
            </div>
        </div>

        <!-- Grafik 1: Per Hari Per Poli (Real-time) -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div
                        class="card-header bg-white rounded-top-4 d-flex justify-content-between align-items-center flex-wrap">
                        <span><i class="fas fa-chart-simple me-2 text-success"></i> <strong> Per Hari Per Poli
                                (Real-time)</strong></span>
                        <span class="badge bg-success mt-1 mt-sm-0"><i class="fas fa-waveform"></i> Update otomatis
                            setiap 20 detik</span>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="chartRjHarian"></canvas>
                        </div>
                        <div class="small-stat mt-3 text-center text-muted">
                            <i class="fas fa-database"></i> Data real-time simulasi berdasarkan pendaftaran JKN Mobile
                            hari ini
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik 2: Per Periode Per Poli -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white rounded-top-4">
                        <span><i class="fas fa-calendar-week me-2 text-warning"></i> <strong> Per Periode 
                                - per Poli</strong></span>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="chartRjPeriode"></canvas>
                        </div>
                        <div class="small-stat mt-3 text-center text-muted">
                            <i class="far fa-calendar-alt"></i> Periode: 01 April - 30 April 2025
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: RAWAT INAP -->
        <div class="row mb-4 mt-3">
            <div class="col-12">
                <h3 class="fw-bold text-info mb-0"><i class="fas fa-bed me-2"></i>Rawat Inap
                </h3>
                <hr class="mt-2 mb-3">
            </div>
        </div>

        <!-- Grafik 3: Per Hari Per Ruang Rawat Inap (Real-time) - Sekarang single bar chart dengan urutan tertinggi ke terendah -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white rounded-top-4 d-flex justify-content-between align-items-center flex-wrap">
                        <span><i class="fas fa-clock me-2 text-danger"></i> <strong> Per Hari Per Ruang Rawat Inap
                                (Real-time)</strong></span>
                        <span class="badge bg-danger mt-1 mt-sm-0">live occupancy</span>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="chartRiHarian"></canvas>
                        </div>
                        <div class="small-stat mt-3 text-center text-muted">
                            <i class="fas fa-chart-line"></i> Jumlah pasien terisi per ruang hari ini (diurutkan dari tertinggi ke terendah)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik 4: Per Periode Per Ruang Rawat Inap -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white rounded-top-4">
                        <span><i class="fas fa-chart-pie me-2 text-secondary"></i> <strong> Per Periode 
                                per Ruang Rawat Inap</strong></span>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="chartRiPeriode"></canvas>
                        </div>
                        <div class="small-stat mt-3 text-center text-muted">
                            <i class="fas fa-chart-simple"></i> Total pasien masuk selama periode April 2025
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="text-center mt-3 pt-3 text-muted">
            <i class="fas fa-mobile-alt"></i> Monitoring Terintegrasi dengan JKN Mobile • Data simulasi dinamis •
            <span id="refreshTime"></span>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ======================== DATA SIMULASI JKN MOBILE ========================

        // Registrasi plugin datalabels untuk menampilkan angka di atas bar
        Chart.register(ChartDataLabels);
        
        // Konfigurasi global untuk datalabels
        const datalabelsConfig = {
            anchor: 'end',
            align: 'top',
            offset: 4,
            color: '#333',
            fontWeight: 'bold',
            font: { size: 12 },
            formatter: function(value) {
                return value;
            }
        };

        // Daftar Poli Rawat Jalan
        const poliList = [
            "Poli Umum", "Poli Anak", "Poli Kebidanan",
            "Poli Jantung", "Poli Saraf", "Poli Mata", "Poli THT"
        ];

        // Daftar Ruang Rawat Inap
        const ruangInapList = [
            "Flamboyan", "Melati", "Anggrek", "Mawar", "Cempaka"
        ];

        // Data untuk Periode Rawat Jalan (sudah sesuai gambar, diurutkan dari terbanyak ke tersedikit)
        const periodeRJData = {
            labels: ["Poli Kebidanan", "Poli Anak", "Poli Saraf", "Poli Mata", "Poli THT", "Poli Umum", "Poli Jantung"],
            data: [340, 310, 285, 280, 175, 165, 125]
        };

        // Fungsi generate rawat jalan harian (real-time) - diurutkan dari terbanyak ke tersedikit
        function generateRJHarianSorted() {
            const rawData = poliList.map(poli => Math.floor(Math.random() * 50) + 8);
            const combined = poliList.map((label, idx) => ({ label, value: rawData[idx] }));
            combined.sort((a, b) => b.value - a.value);
            return {
                labels: combined.map(item => item.label),
                data: combined.map(item => item.value)
            };
        }

        // Fungsi generate rawat inap harian (real-time) - SINGLE BAR CHART (hanya Terisi)
        // Diurutkan dari jumlah terisi tertinggi ke terendah
        function generateRIHarianSorted() {
            const totalBedMap = {
                "Flamboyan": 32,
                "Melati": 28,
                "Anggrek": 24,
                "Mawar": 30,
                "Cempaka": 22
            };
            
            // Generate data terisi random
            const terisiData = ruangInapList.map(() => Math.floor(Math.random() * 25) + 10);
            const combined = ruangInapList.map((nama, idx) => ({
                label: nama,
                terisi: terisiData[idx],
                totalBed: totalBedMap[nama]
            }));
            
            // Urutkan berdasarkan terisi dari terbanyak ke tersedikit
            combined.sort((a, b) => b.terisi - a.terisi);
            
            return {
                labels: combined.map(item => item.label),
                terisi: combined.map(item => item.terisi),
                totalBed: combined.map(item => item.totalBed)
            };
        }

        // Data Rawat Inap Periode (bulanan) - diurutkan dari terbanyak ke tersedikit
        function generateRIPeriodeSorted() {
            const rawData = ruangInapList.map(() => Math.floor(Math.random() * 120) + 50);
            const combined = ruangInapList.map((label, idx) => ({ label, value: rawData[idx] }));
            combined.sort((a, b) => b.value - a.value);
            return {
                labels: combined.map(item => item.label),
                data: combined.map(item => item.value)
            };
        }

        // Charts global
        let chartRjHarian, chartRjPeriode, chartRiHarian, chartRiPeriode;

        // Warna chart
        const colorBlue = 'rgba(54, 162, 235, 0.8)';
        const colorGreen = 'rgba(75, 192, 192, 0.8)';
        const colorOrange = 'rgba(255, 159, 64, 0.8)';
        const colorRed = 'rgba(255, 99, 132, 0.8)';
        const borderBlue = 'rgba(54, 162, 235, 1)';

        // 1. RENDER GRAFIK RAWAT JALAN HARIAN (Bar Chart dengan urutan & angka di atas)
        function renderRJHarian() {
            const { labels, data } = generateRJHarianSorted();

            if (chartRjHarian) chartRjHarian.destroy();
            const ctx = document.getElementById('chartRjHarian').getContext('2d');
            chartRjHarian = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Pendaftaran Hari Ini',
                        data: data,
                        backgroundColor: colorBlue,
                        borderColor: borderBlue,
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.7,
                        datalabels: datalabelsConfig
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 20
                        }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: { callbacks: { label: (ctx) => `${ctx.raw} pasien` } },
                        datalabels: datalabelsConfig
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Jumlah Pasien' },
                            grid: { color: '#e9ecef' }
                        },
                        x: {
                            title: { display: true, text: 'Poli Rawat Jalan' },
                            ticks: { autoSkip: false, maxRotation: 45, minRotation: 45 }
                        }
                    }
                }
            });
        }

        // 2. RENDER GRAFIK RAWAT JALAN PERIODE (Bar Chart - sesuai gambar, diurutkan terbanyak ke tersedikit)
        function renderRJPeriode() {
            const labels = periodeRJData.labels;
            const dataValues = periodeRJData.data;

            if (chartRjPeriode) chartRjPeriode.destroy();
            const ctx = document.getElementById('chartRjPeriode').getContext('2d');
            chartRjPeriode = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Pendaftaran (Periode Bulan Ini)',
                        data: dataValues,
                        backgroundColor: colorGreen,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.7,
                        datalabels: datalabelsConfig
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: { top: 20 }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: { callbacks: { label: (ctx) => `${ctx.raw} pasien` } },
                        datalabels: datalabelsConfig
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Total Pendaftaran' },
                            grid: { color: '#e9ecef' }
                        },
                        x: {
                            title: { display: true, text: 'Poli Rawat Jalan' },
                            ticks: { autoSkip: false, maxRotation: 45, minRotation: 45 }
                        }
                    }
                }
            });
        }

        // 3. RENDER GRAFIK RAWAT INAP HARIAN (SINGLE BAR CHART - hanya Terisi)
        // Diurutkan dari tertinggi ke terendah, sama seperti grafik lainnya
        function renderRIHarian() {
            const { labels, terisi, totalBed } = generateRIHarianSorted();

            if (chartRiHarian) chartRiHarian.destroy();
            const ctx = document.getElementById('chartRiHarian').getContext('2d');
            chartRiHarian = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Pasien Terisi (Hari Ini)',
                            data: terisi,
                            backgroundColor: colorOrange,
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1,
                            borderRadius: 6,
                            barPercentage: 0.7,
                            datalabels: datalabelsConfig
                        },
                        {
                            label: 'Total Tempat Tidur',
                            data: totalBed,
                            backgroundColor: 'rgba(200, 200, 200, 0.3)',
                            borderRadius: 6,
                            type: 'line',
                            borderColor: '#6c757d',
                            borderWidth: 2,
                            fill: false,
                            tension: 0.1,
                            pointRadius: 5,
                            pointBackgroundColor: '#6c757d',
                            pointBorderColor: '#6c757d',
                            datalabels: {
                                ...datalabelsConfig,
                                align: 'top',
                                anchor: 'end',
                                color: '#555',
                                font: { size: 11 }
                            }
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: { top: 25 }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: { 
                            callbacks: { 
                                label: (ctx) => `${ctx.dataset.label}: ${ctx.raw} pasien` 
                            } 
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            offset: 4,
                            color: '#333',
                            fontWeight: 'bold',
                            font: { size: 11 },
                            formatter: function(value, context) {
                                return value;
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Jumlah Pasien' },
                            grid: { color: '#e9ecef' },
                            max: function(context) {
                                // Memberi sedikit ruang di atas untuk label
                                const maxValue = Math.max(...terisi, ...totalBed);
                                return maxValue + 5;
                            }
                        },
                        x: {
                            title: { display: true, text: 'Ruang Rawat Inap' },
                            ticks: { autoSkip: false, maxRotation: 45, minRotation: 45 }
                        }
                    }
                }
            });
        }

        // 4. RENDER GRAFIK RAWAT INAP PERIODE (Bar chart dengan urutan & angka di atas)
        function renderRIPeriode() {
            const { labels, data } = generateRIPeriodeSorted();

            if (chartRiPeriode) chartRiPeriode.destroy();
            const ctx = document.getElementById('chartRiPeriode').getContext('2d');
            chartRiPeriode = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Pasien Masuk (Periode Bulan Ini)',
                        data: data,
                        backgroundColor: colorRed,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderRadius: 8,
                        barPercentage: 0.65,
                        datalabels: datalabelsConfig
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: { top: 20 }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: { callbacks: { label: (ctx) => `${ctx.raw} pasien` } },
                        datalabels: datalabelsConfig
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Total Pasien' },
                            grid: { color: '#e9ecef' }
                        },
                        x: {
                            title: { display: true, text: 'Ruang Rawat Inap' },
                            ticks: { autoSkip: false, maxRotation: 45, minRotation: 45 }
                        }
                    }
                }
            });
        }

        // Update jam dan timestamp
        function updateClock() {
            const now = new Date();
            const formatted = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const clockSpan = document.getElementById('realtimeClock');
            if (clockSpan) clockSpan.innerText = formatted;
            const refreshSpan = document.getElementById('refreshTime');
            if (refreshSpan) refreshSpan.innerText = `Terakhir update: ${now.toLocaleString('id-ID')}`;
        }

        // Refresh semua grafik (simulasi realtime)
        function refreshAllCharts() {
            renderRJHarian();   // Rawat Jalan Harian (fluktuasi realtime, urut)
            renderRIHarian();   // Rawat Inap Harian (single bar, urut dari tertinggi ke terendah)
            renderRIPeriode();  // Rawat Inap Periode (fluktuasi, urut)
            renderRJPeriode();  // Rawat Jalan Periode (statis tapi terurut)
            updateClock();

            // Efek animasi badge
            const badge = document.querySelector('.badge-real-time');
            if (badge) {
                badge.style.opacity = '0.7';
                setTimeout(() => {
                    if (badge) badge.style.opacity = '1';
                }, 200);
            }
        }

        // Inisialisasi awal dan auto refresh 20 detik
        let intervalId;

        function startAutoRefresh() {
            if (intervalId) clearInterval(intervalId);
            intervalId = setInterval(() => {
                refreshAllCharts();
            }, 20000);
        }

        // Inisialisasi semua grafik saat halaman dimuat
        function initDashboard() {
            renderRJHarian();
            renderRJPeriode();
            renderRIHarian();
            renderRIPeriode();
            updateClock();
            startAutoRefresh();
        }

        // Event manual refresh via badge
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => {
                refreshAllCharts();
                const icon = refreshBtn.querySelector('i');
                if (icon) {
                    icon.style.transform = 'rotate(360deg)';
                    setTimeout(() => {
                        if (icon) icon.style.transform = '';
                    }, 400);
                }
            });
        }

        // handle resize charts
        window.addEventListener('resize', () => {
            if (chartRjHarian) chartRjHarian.resize();
            if (chartRjPeriode) chartRjPeriode.resize();
            if (chartRiHarian) chartRiHarian.resize();
            if (chartRiPeriode) chartRiPeriode.resize();
        });

        // Jalankan inisialisasi
        initDashboard();

        // update jam tiap detik
        setInterval(updateClock, 1000);

        console.log("Dashboard 4 Grafik - Monitoring JKN Mobile siap dengan urutan terbanyak ke tersedikit & angka tampil di atas bar");
    </script>
</body>

</html>