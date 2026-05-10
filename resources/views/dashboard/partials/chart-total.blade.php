<div class="two-charts">
    <!-- Chart Rawat Jalan -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center flex-wrap">
            <span>
                <i class="fas fa-stethoscope me-2 text-primary"></i>
                <strong>Rawat Jalan</strong>
            </span>
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
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center flex-wrap">
            <span>
                <i class="fas fa-bed me-2" style="color: #f59e0b;"></i>
                <strong>Rawat Inap</strong>
            </span>
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

    // Overlay plugin untuk menggambar batang total di background
    const totalOverlayPlugin = {
        id: 'totalOverlayPlugin',
        afterDatasetsDraw(chart) {
            const opts = chart.config.options.plugins?.overlayBar;
            if (!opts) return;

            const { ctx } = chart;
            const meta = chart.getDatasetMeta(0); // Dataset Target
            const targetBar = meta.data[0];

            if (!targetBar) return;

            // Ambil posisi batang target
            const cx = targetBar.x;
            const yBottom = targetBar.base;
            const yScale = chart.scales.y;
            
            // Hitung posisi untuk batang total
            const totalValue = opts.value;
            const targetValue = opts.targetValue;
            
            // Hitung Y top untuk batang total
            let yTopTotal = yScale.getPixelForValue(totalValue);
            let barHeight = yBottom - yTopTotal;
            
            // Lebar batang total (70% dari lebar target agar terlihat sebagai background)
            const barWidth = targetBar.width * 0.70;
            const barX = cx - barWidth / 2;
            const borderRadius = Math.min(8, barWidth / 2, Math.abs(barHeight) / 2);
            
            ctx.save();
            
            // Gambar batang total di background
            ctx.beginPath();
            
            if (barHeight > 0) {
                // Batang total positif (normal)
                ctx.moveTo(barX + borderRadius, yTopTotal);
                ctx.lineTo(barX + barWidth - borderRadius, yTopTotal);
                ctx.quadraticCurveTo(barX + barWidth, yTopTotal, barX + barWidth, yTopTotal + borderRadius);
                ctx.lineTo(barX + barWidth, yBottom);
                ctx.lineTo(barX, yBottom);
                ctx.lineTo(barX, yTopTotal + borderRadius);
                ctx.quadraticCurveTo(barX, yTopTotal, barX + borderRadius, yTopTotal);
            } else {
                // Batang total negatif (tidak mungkin terjadi karena nilai tidak negatif)
                const yTopTemp = yBottom;
                const yBottomTemp = yScale.getPixelForValue(totalValue);
                ctx.moveTo(barX + borderRadius, yTopTemp);
                ctx.lineTo(barX + barWidth - borderRadius, yTopTemp);
                ctx.quadraticCurveTo(barX + barWidth, yTopTemp, barX + barWidth, yTopTemp + borderRadius);
                ctx.lineTo(barX + barWidth, yBottomTemp);
                ctx.lineTo(barX, yBottomTemp);
                ctx.lineTo(barX, yTopTemp + borderRadius);
                ctx.quadraticCurveTo(barX, yTopTemp, barX + borderRadius, yTopTemp);
            }
            
            ctx.closePath();
            
            // Fill dengan warna total tapi transparan (background)
            ctx.fillStyle = opts.color + '80'; // Tambah opacity 50%
            ctx.fill();
            ctx.strokeStyle = opts.borderColor + 'cc';
            ctx.lineWidth = 2;
            ctx.stroke();
            
            // HANYA gambar label nilai total di atas batang total
            // TIDAK menampilkan text melebihi target di sini
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 14px sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'bottom';
            ctx.fillText(totalValue.toLocaleString('id-ID'), cx, yTopTotal - 8);
            
            ctx.restore();
        }
    };

    // Register plugin
    Chart.register(totalOverlayPlugin);

    // Build chart configuration
    function buildTotalChartConfig(targetValue, totalValue, totalColor, totalBorderColor, yMax, yTitle) {
        // Tentukan nilai maksimum untuk skala Y (dengan buffer)
        const maxValue = Math.max(targetValue, totalValue);
        const yAxisMax = maxValue + (maxValue * 0.2); // Tambah 20% buffer untuk label
        
        return {
            type: 'bar',
            data: {
                labels: [''],
                datasets: [
                    {
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
                        top: 30, // Padding top lebih kecil karena tidak ada label exceed
                        bottom: 10 
                    } 
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#ffffff',
                            boxWidth: 14,
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'rect',
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            generateLabels: (chart) => {
                                const labels = [
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
                                        text: `Total (${totalValue.toLocaleString('id-ID')})`,
                                        fillStyle: totalColor,
                                        strokeStyle: totalBorderColor,
                                        lineWidth: 2,
                                        pointStyle: 'rect',
                                        fontColor: '#ffffff',
                                        color: '#ffffff'
                                    }
                                ];
                                
                                return labels;
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#111827',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        callbacks: {
                            label: (ctx) => {
                                if (ctx.dataset.label === 'Target') {
                                    return `Target: ${ctx.raw.toLocaleString('id-ID')}`;
                                }
                                return `${ctx.dataset.label}: ${ctx.raw.toLocaleString('id-ID')}`;
                            }
                        }
                    },
                    datalabels: {
                        color: '#ffffff',
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
                        targetValue: targetValue,
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
                            color: '#ffffff'
                        },
                        offset: true
                    },
                    y: {
                        beginAtZero: true,
                        max: yAxisMax,
                        grid: { 
                            color: 'rgba(255,255,255,0.08)' 
                        },
                        ticks: { 
                            color: '#ffffff',
                            font: { 
                                size: 13,
                                weight: 'bold'
                            },
                            callback: (value) => {
                                return value.toLocaleString('id-ID');
                            }
                        },
                        title: { 
                            display: true, 
                            text: yTitle, 
                            color: '#ffffff',
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

            // Update footers - HANYA MENAMPILKAN INFORMASI DI FOOTER
            const rajalTotal = rawatJalan.total;
            const rajalTarget = rawatJalan.target;
            const rajalExceed = rajalTotal > rajalTarget;
            const rajalRemaining = rajalTarget - rajalTotal;
            
            const ranapTotal = rawatInap.total;
            const ranapTarget = rawatInap.target;
            const ranapExceed = ranapTotal > ranapTarget;
            const ranapRemaining = ranapTarget - ranapTotal;
            
            // Footer untuk Rawat Jalan
            let rajalFooterHtml = `<i class="fas fa-database"></i> Total ${rajalTotal.toLocaleString('id-ID')} / Target ${rajalTarget.toLocaleString('id-ID')}`;
            if (rajalExceed) {
                rajalFooterHtml += ` <span style="color: #10b981;"><i class="fas fa-check-circle"></i> Berhasil melebihi target +${(rajalTotal - rajalTarget).toLocaleString('id-ID')}</span>`;
            } else if (rajalRemaining > 0) {
                rajalFooterHtml += ` <span style="color: #ef4444;"><i class="fas fa-chart-line"></i> Sisa target: ${rajalRemaining.toLocaleString('id-ID')}</span>`;
            } else {
                rajalFooterHtml += ` <span style="color: #10b981;"><i class="fas fa-trophy"></i> Tepat mencapai target!</span>`;
            }
            document.getElementById('totalRajalFooter').innerHTML = rajalFooterHtml;
            
            // Footer untuk Rawat Inap
            let ranapFooterHtml = `<i class="fas fa-database"></i> Total ${ranapTotal.toLocaleString('id-ID')} / Target ${ranapTarget.toLocaleString('id-ID')}`;
            if (ranapExceed) {
                ranapFooterHtml += ` <span style="color: #10b981;"><i class="fas fa-check-circle"></i> Berhasil melebihi target +${(ranapTotal - ranapTarget).toLocaleString('id-ID')}</span>`;
            } else if (ranapRemaining > 0) {
                ranapFooterHtml += ` <span style="color: #ef4444;"><i class="fas fa-chart-line"></i> Sisa target: ${ranapRemaining.toLocaleString('id-ID')}</span>`;
            } else {
                ranapFooterHtml += ` <span style="color: #10b981;"><i class="fas fa-trophy"></i> Tepat mencapai target!</span>`;
            }
            document.getElementById('totalRanapFooter').innerHTML = ranapFooterHtml;

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