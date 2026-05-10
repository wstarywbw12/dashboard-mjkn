<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center flex-wrap">
        <span>
            <i class="fas fa-bed me-2" style="color: #f59e0b;"></i>
            <strong id="ranapChartTitle">Per Hari Per Ruang Rawat Inap </strong> <span class="d-none d-md-inline">(Real-time)</span>
        </span>
        <span class="badge bg-info mt-1 mt-sm-0" id="ranapChartSubtitle">
            <i class="fas fa-waveform"></i> Data Real-time
        </span>
    </div>
    <div class="card-body position-relative">
        <div class="chart-container">
            <canvas id="ranapChart"></canvas>
        </div>
        <div class="small-stat mt-3 text-center text-white" id="ranapChartFooter">
            <i class="fas fa-database"></i> Memuat data...
        </div>
        <div id="ranapLoadingOverlay" class="loading-overlay" style="display: none;">
            <div class="loading-spinner"></div>
        </div>
    </div>
</div>

<script>
    // Chart instance
    let ranapChartInstance = null;

    // Datalabels configuration
    const ranapDatalabelsConfig = {
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

    // Load Rawat Inap chart data
    window.loadRawatInapChart = async (periodType, startDate = null, endDate = null) => {
        const canvas = document.getElementById('ranapChart');
        if (!canvas) return;

        // Show loading
        const loadingOverlay = document.getElementById('ranapLoadingOverlay');
        if (loadingOverlay) loadingOverlay.style.display = 'flex';

        try {
            // Prepare request parameters
            const params = { period_type: periodType };
            if (startDate) params.start_date = moment(startDate).format('YYYY-MM-DD');
            if (endDate) params.end_date = moment(endDate).format('YYYY-MM-DD');

            // Fetch data from API
            const response = await window.fetchChartData('{{ route("api.rawat-inap") }}', params);
            
            if (!response.success) throw new Error('Failed to fetch data');

            const data = response.data;
            const meta = response.meta;

            // Update title and subtitle
            const titleMap = {
                'daily': 'Per Hari Per Ruang Rawat Inap',
                'period': 'Per Periode - Per Ruang Rawat Inap'
            };
            document.getElementById('ranapChartTitle').innerHTML = `<i class="fas fa-chart-simple me-2"></i> ${titleMap[periodType]}`;

            const subtitleText = periodType === 'daily' ? 'Data Real-time (update otomatis setiap 20 detik)' :
                (startDate && endDate ? `Periode: ${moment(startDate).format('DD/MM/YYYY')} - ${moment(endDate).format('DD/MM/YYYY')}` : 'Periode terpilih');
            document.getElementById('ranapChartSubtitle').innerHTML = `<i class="fas fa-calendar-alt me-1"></i> ${subtitleText}`;
            
            const total = data.total || data.values.reduce((a, b) => a + b, 0);
            const target = data.target || 11200;
            document.getElementById('ranapChartFooter').innerHTML = `<i class="fas fa-database"></i> Total ${total.toLocaleString('id-ID')} / Target ${target.toLocaleString('id-ID')} pendaftaran JKN Mobile`;

            // Destroy existing chart
            if (ranapChartInstance) {
                ranapChartInstance.destroy();
            }

            // Create new chart
            const ctx = canvas.getContext('2d');
            ranapChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: periodType === 'daily' ? 'Jumlah Pendaftaran Hari Ini' : 'Total Pendaftaran (Periode)',
                        data: data.values,
                        backgroundColor: '#f59e0b',
                        borderColor: '#f59e0b',
                        borderWidth: 2,
                        borderRadius: 8,
                        barPercentage: 0.65,
                        categoryPercentage: 0.8,
                        datalabels: ranapDatalabelsConfig
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
                                font: { size: 12 }
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
                        datalabels: ranapDatalabelsConfig
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
                            grid: { display: false },
                            title: {
                                display: true,
                                text: 'Ruang Rawat Inap',
                                color: '#9ca3af'
                            },
                            ticks: {
                                color: '#e2e8f0',
                                autoSkip: false,
                                maxRotation: 45,
                                minRotation: 45,
                                font: { size: 11 }
                            }
                        }
                    }
                }
            });

        } catch (error) {
            console.error('Error loading Rawat Inap chart:', error);
            document.getElementById('ranapChartFooter').innerHTML = '<i class="fas fa-exclamation-triangle"></i> Gagal memuat data';
        } finally {
            if (loadingOverlay) loadingOverlay.style.display = 'none';
        }
    };

    // Handle window resize
    window.addEventListener('resize', () => {
        if (ranapChartInstance) ranapChartInstance.resize();
    });
</script>