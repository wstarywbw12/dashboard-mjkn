<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard JKN Mobile')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- Plugin untuk menampilkan data label di atas bar -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
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
            font-size: clamp(0.85rem, 3vw, 1rem);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 55vw;
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
            padding-bottom: 0.7rem;
            padding-top: 0.3rem;
            padding-left: 1.25rem;
            padding-right: 1.25rem;
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

        .badge {
            font-size: clamp(0.65rem, 2.5vw, 0.75rem);
            white-space: nowrap;
        }

        .badge-real-time {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            font-size: 0.7rem;
            animation: pulse 1.5s infinite;
            cursor: pointer;
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

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            z-index: 10;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid var(--border-dark);
            border-top-color: var(--accent-blue);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .position-relative {
            position: relative;
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark shadow-sm py-2 py-md-3">
        <div class="container-fluid px-3 px-md-4">
            <span class="navbar-brand fs-6 fs-md-5">
                <span class="d-none d-sm-inline">Dashboard Monitoring </span>
                <strong>JKN Mobile</strong>
            </span>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark d-flex align-items-center">
                    <i class="far fa-clock me-1"></i>
                    <span id="realtimeClock" class="font-monospace" style="min-width: 58px; font-size: 0.72rem;"></span>
                </span>
                <span class="badge badge-real-time d-flex align-items-center" id="refreshBtn">
                    <i class="fas fa-sync-alt me-1"></i>
                    <span class="d-none d-sm-inline">Refresh</span>
                </span>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-3 px-md-4 py-4">
        @yield('content')
    </div>

    <footer class="text-center mt-4 pt-3 text-white">
        <i class="fas fa-mobile-alt"></i> Monitoring Terintegrasi dengan JKN Mobile
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Global functions
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

        setInterval(updateClock, 1000);
        updateClock();

        // Fungsi untuk fetch data dari API
        window.fetchChartData = async (url, params = {}) => {
            const queryString = new URLSearchParams(params).toString();
            const fullUrl = queryString ? `${url}?${queryString}` : url;
            
            try {
                const response = await fetch(fullUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (!response.ok) throw new Error('Network response was not ok');
                return await response.json();
            } catch (error) {
                console.error('Fetch error:', error);
                throw error;
            }
        };
    </script>

    @stack('scripts')
</body>

</html>