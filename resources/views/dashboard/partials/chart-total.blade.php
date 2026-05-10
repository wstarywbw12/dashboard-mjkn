<div class="two-charts">
    <!-- Chart Rawat Jalan -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-transparent">
            <span><i class="fas fa-stethoscope me-2 text-primary"></i> <strong>Rawat Jalan</strong></span>
        </div>
        <div class="card-body position-relative">
            <div class="chart-container" style="height: 380px;">
                <canvas id="totalRajalChart"></canvas>
            </div>
            <div class="small-stat mt-2 text-center text-white" id="totalRajalFooter">
                <i class="fas fa-database"></i> Memuat data...
            </div>
            <div id="totalRajalLoadingOverlay" class="loading-overlay" style="display: none;">
                <div class="loading-spinner"></div>
            </div>
        </div>
    </div>

    <!-- Chart Rawat Inap -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-transparent">
            <span><i class="fas fa-bed" style="color: #f59e0b;"></i> <strong>Rawat Inap</strong></span>
        </div>
        <div class="card-body position-relative">
            <div class="chart-container" style="height: 380px;">
                <canvas id="totalRanapChart"></canvas>
            </div>
            <div class="small-stat mt-2 text-center text-white" id="totalRanapFooter">
                <i class="fas fa-database"></i> Memuat data...
            </div>
            <div id="totalRanapLoadingOverlay" class="loading-overlay" style="display: none;">
                <div class="loading-spinner"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // Chart instances
    let totalRajalChartInstance = null;
    let totalRanapChartInstance = null;

    // Overlay plugin for total charts
    const totalOverlayPlugin = {
        id: 'totalOverlayPlugin',
        afterDatasetsDraw(chart) {
            const opts = chart.config.options.plugins?.overlayBar;
            if (!opts) return;

            const { ctx } = chart;
            const meta = chart.getDatasetMeta(0);
            const targetBar = meta.data[0];

            if (!targetBar) return;

            const cx = targetBar.x;
            const yBottom = targetBar.base;
            const yScale = chart.scales.y;
            const yTop = yScale.getPixelForValue(opts.value);
            const barH = yBottom - yTop;

            if (barH <= 0) return;

            const w = targetBar.width * 0.50;
            const x = cx - w / 2;
            const r = Math.min(12, w / 2, barH / 2);

            ctx.save();

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

            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 17px sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'bottom';
            ctx.fillText(opts.value.toLocaleString('id-ID'), cx, yTop - 6);

            ctx.restore();
        }
    };

    // Register plugin
    Chart.register(totalOverlayPlugin);

    // Build chart configuration
    function buildTotalChartConfig(targetValue, totalValue, totalColor, totalBorderColor, yMax, yTitle) {
        return {
            type: 'bar',
            data: {
                labels: [''],
                datasets: [{
                    label: 'Target',
                    data: [targetValue],
                    backgroundColor: '#2b004a',
                    borderColor: '#930ff2',
                    borderWidth: 2,
                    borderRadius: 12,
                    barPercentage: 0.55,
                    categoryPercentage: 0.9,
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        offset: 6,
                        color: '#ffffff',  // Warna putih untuk label Target
                        font: { 
                            size: 15, 
                            weight: 'bold' 
                        },
                        formatter: (v) => v.toLocaleString('id-ID')
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { 
                    padding: { 
                        top: 20, 
                        bottom: 10 
                    } 
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#ffffff',  // Warna putih untuk teks legend
                            boxWidth: 14,
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'rect',
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            generateLabels: (chart) => {
                                return [
                                    {
                                        text: 'Target',
                                        fillStyle: '#2b004a',
                                        strokeStyle: '#930ff2',
                                        lineWidth: 2,
                                        pointStyle: 'rect',
                                        fontColor: '#ffffff',
                                        color: '#ffffff'
                                    },
                                    {
                                        text: 'Total',
                                        fillStyle: totalColor,
                                        strokeStyle: totalBorderColor,
                                        lineWidth: 2,
                                        pointStyle: 'rect',
                                        fontColor: '#ffffff',
                                        color: '#ffffff'
                                    }
                                ];
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#111827',
                        titleColor: '#ffffff',  // Warna putih untuk title tooltip
                        bodyColor: '#ffffff',   // Warna putih untuk body tooltip
                        callbacks: {
                            label: (ctx) => {
                                return `${ctx.dataset.label}: ${ctx.raw.toLocaleString('id-ID')}`;
                            }
                        }
                    },
                    datalabels: {
                        color: '#ffffff',  // Warna putih untuk data labels
                        font: {
                            size: 15,
                            weight: 'bold'
                        },
                        formatter: (value) => {
                            return value.toLocaleString('id-ID');
                        }
                    },
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
                            display: false,
                            color: '#ffffff'  // Warna putih untuk ticks
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
                            color: '#ffffff',  // Warna putih untuk ticks Y axis
                            font: { 
                                size: 13,
                                weight: 'bold'
                            } 
                        },
                        title: { 
                            display: true, 
                            text: yTitle, 
                            color: '#ffffff',  // Warna putih untuk title Y axis
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        };
    }

    // Load Total charts data
    window.loadTotalCharts = async (periodType, startDate = null, endDate = null) => {
        // Show loading overlays
        const rajalLoading = document.getElementById('totalRajalLoadingOverlay');
        const ranapLoading = document.getElementById('totalRanapLoadingOverlay');
        if (rajalLoading) rajalLoading.style.display = 'flex';
        if (ranapLoading) ranapLoading.style.display = 'flex';

        try {
            const params = { period_type: periodType };
            if (startDate) params.start_date = moment(startDate).format('YYYY-MM-DD');
            if (endDate) params.end_date = moment(endDate).format('YYYY-MM-DD');

            const response = await window.fetchChartData('{{ route("api.total") }}', params);
            
            if (!response.success) throw new Error('Failed to fetch data');

            const rawatJalan = response.data.rawat_jalan;
            const rawatInap = response.data.rawat_inap;

            // Update footers
            document.getElementById('totalRajalFooter').innerHTML = 
                `<i class="fas fa-database"></i> Total ${rawatJalan.total.toLocaleString('id-ID')} / Target ${rawatJalan.target.toLocaleString('id-ID')}`;
            document.getElementById('totalRanapFooter').innerHTML = 
                `<i class="fas fa-database"></i> Total ${rawatInap.total.toLocaleString('id-ID')} / Target ${rawatInap.target.toLocaleString('id-ID')}`;

            // Destroy existing charts
            if (totalRajalChartInstance) totalRajalChartInstance.destroy();
            if (totalRanapChartInstance) totalRanapChartInstance.destroy();

            // Create new charts
            totalRajalChartInstance = new Chart(
                document.getElementById('totalRajalChart').getContext('2d'),
                buildTotalChartConfig(
                    rawatJalan.target, 
                    rawatJalan.total,
                    '#3b82f6', 
                    '#60a5fa',
                    rawatJalan.target + 300,
                    'Jumlah Pendaftaran'
                )
            );

            totalRanapChartInstance = new Chart(
                document.getElementById('totalRanapChart').getContext('2d'),
                buildTotalChartConfig(
                    rawatInap.target, 
                    rawatInap.total,
                    '#f59e0b', 
                    '#fbbf24',
                    rawatInap.target + 2000,
                    'Jumlah Pendaftaran'
                )
            );

        } catch (error) {
            console.error('Error loading Total charts:', error);
            document.getElementById('totalRajalFooter').innerHTML = '<i class="fas fa-exclamation-triangle"></i> Gagal memuat data';
            document.getElementById('totalRanapFooter').innerHTML = '<i class="fas fa-exclamation-triangle"></i> Gagal memuat data';
        } finally {
            if (rajalLoading) rajalLoading.style.display = 'none';
            if (ranapLoading) ranapLoading.style.display = 'none';
        }
    };

    // Handle window resize
    window.addEventListener('resize', () => {
        if (totalRajalChartInstance) totalRajalChartInstance.resize();
        if (totalRanapChartInstance) totalRanapChartInstance.resize();
    });
</script>