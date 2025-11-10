// Reports and Analytics JavaScript

$(document).ready(function() {
    
    // Period filter change handler
    $('#reportPeriod').on('change', function() {
        const period = $(this).val();
        loadReportData(period);
    });

    // Export PDF functionality
    $('.export-pdf-btn, button:contains("Export PDF")').on('click', function(e) {
        e.preventDefault();
        exportReportToPDF();
    });

    // Load report data based on selected period
    function loadReportData(period) {
        $.ajax({
            url: 'index.php?controller=Admin&action=getReportData',
            method: 'GET',
            data: { period: period },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    updateCharts(response.data);
                    updateKPIs(response.data);
                }
            },
            error: function() {
                showAlert('Error loading report data', 'error');
            }
        });
    }

    // Update charts with new data
    function updateCharts(data) {
        // Update revenue trend chart
        if(window.revenueTrendChart) {
            window.revenueTrendChart.data.labels = data.revenue_trend.labels;
            window.revenueTrendChart.data.datasets[0].data = data.revenue_trend.values;
            window.revenueTrendChart.update();
        }

        // Update other charts similarly
    }

    // Update KPI cards
    function updateKPIs(data) {
        // Update KPI values dynamically
        $('.total-revenue').text('₱' + formatNumber(data.total_revenue));
        $('.pending-revenue').text('₱' + formatNumber(data.pending_revenue));
        $('.active-members').text(data.active_members);
        $('.retention-rate').text(data.retention_rate + '%');
    }

    // Format number with commas
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // Export report to PDF
    function exportReportToPDF() {
        showAlert('Generating PDF report...', 'info');
        
        // You can use a library like jsPDF or send request to server
        window.print(); // Simple print dialog for now
    }

    // Show alert message
    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'bg-green-600' : 
                          type === 'error' ? 'bg-red-600' : 'bg-blue-600';
        
        const alert = $(`
            <div class="alert ${alertClass} text-white px-6 py-4 rounded-lg shadow-lg mb-4">
                ${message}
            </div>
        `);
        
        $('#alertContainer').append(alert);
        
        setTimeout(() => {
            alert.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }

    // Print specific report section
    window.printReport = function() {
        const printContent = document.getElementById('reports').innerHTML;
        const originalContent = document.body.innerHTML;
        
        document.body.innerHTML = `
            <html>
            <head>
                <title>Gymazing - Report</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .stat-card { 
                        border: 1px solid #ccc; 
                        padding: 20px; 
                        margin: 10px;
                        display: inline-block;
                        width: 23%;
                    }
                    canvas { max-width: 100%; }
                    @media print {
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <h1>Gymazing Fitness - Analytics Report</h1>
                <p>Generated: ${new Date().toLocaleDateString()}</p>
                ${printContent}
            </body>
            </html>
        `;
        
        window.print();
        document.body.innerHTML = originalContent;
        location.reload();
    };

    // Real-time data refresh (optional)
    function startAutoRefresh() {
        setInterval(function() {
            const period = $('#reportPeriod').val();
            loadReportData(period);
        }, 300000); // Refresh every 5 minutes
    }

    // Uncomment to enable auto-refresh
    // startAutoRefresh();
});

// Chart interaction handlers
function chartClickHandler(evt, item) {
    if(item.length > 0) {
        const index = item[0].index;
        const label = item[0].chart.data.labels[index];
        console.log('Clicked:', label);
        // Add custom drill-down functionality here
    }
}