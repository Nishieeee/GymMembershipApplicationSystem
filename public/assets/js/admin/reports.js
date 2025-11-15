// Reports and Analytics JavaScript

$(document).ready(function () {
  // Period filter change handler
  $("#reportPeriod").on("change", function () {
    const period = $(this).val();
    loadReportData(period);
  });

  // Export PDF functionality
  $('.export-pdf-btn, button:contains("Export PDF")').on("click", function (e) {
    e.preventDefault();
    exportReportToPDF();
  });

  // Load report data based on selected period
  function loadReportData(period) {
    $.ajax({
      url: "index.php?controller=Admin&action=getReportData",
      method: "GET",
      data: { period: period },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          updateCharts(response.data);
          updateKPIs(response.data);
        }
      },
      error: function () {
        showAlert("Error loading report data", "error");
      },
    });
  }

  // Update charts with new data
  function updateCharts(data) {
    // Update revenue trend chart
    if (window.revenueTrendChart) {
      window.revenueTrendChart.data.labels = data.revenue_trend.labels;
      window.revenueTrendChart.data.datasets[0].data =
        data.revenue_trend.values;
      window.revenueTrendChart.update();
    }

    // Update other charts similarly
  }

  // Update KPI cards
  function updateKPIs(data) {
    // Update KPI values dynamically
    $(".total-revenue").text("₱" + formatNumber(data.total_revenue));
    $(".pending-revenue").text("₱" + formatNumber(data.pending_revenue));
    $(".active-members").text(data.active_members);
    $(".retention-rate").text(data.retention_rate + "%");
  }

  // Format number with commas
  function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  // Export report to PDF
  function exportReportToPDF() {
    showAlert("Generating PDF report...", "info");

    // You can use a library like jsPDF or send request to server
    window.print(); // Simple print dialog for now
  }

  // Show alert message
  function showAlert(message, type) {
    const alertClass =
      type === "success"
        ? "bg-green-600"
        : type === "error"
        ? "bg-red-600"
        : "bg-blue-600";

    const alert = $(`
            <div class="alert ${alertClass} text-white px-6 py-4 rounded-lg shadow-lg mb-4">
                ${message}
            </div>
        `);

    $("#alertContainer").append(alert);

    setTimeout(() => {
      alert.fadeOut(300, function () {
        $(this).remove();
      });
    }, 3000);
  }

  function printReportPDF() {
    // Add print class to body
    document.body.classList.add("printing");

    // Set print-friendly title
    const originalTitle = document.title;
    document.title = `Gymazing Report - ${new Date().toLocaleDateString()}`;

    // Trigger print
    setTimeout(() => {
      window.print();

      // Restore after print
      setTimeout(() => {
        document.title = originalTitle;
        document.body.classList.remove("printing");
      }, 100);
    }, 500);
  }
  // Real-time data refresh (optional)
  function startAutoRefresh() {
    setInterval(function () {
      const period = $("#reportPeriod").val();
      loadReportData(period);
    }, 300000); // Refresh every 5 minutes
  }

  // Uncomment to enable auto-refresh
  // startAutoRefresh();
});

// Chart interaction handlers
function chartClickHandler(evt, item) {
  if (item.length > 0) {
    const index = item[0].index;
    const label = item[0].chart.data.labels[index];
    console.log("Clicked:", label);
    // Add custom drill-down functionality here
  }
}
// Add to reports.js or create admin-reports-filter.js

$(document).ready(function () {
  // Store chart instances globally
  window.chartInstances = {};

  // Show/hide custom date range
  $("#dateRangeFilter").on("change", function () {
    if ($(this).val() === "custom") {
      $("#customDateRange").removeClass("hidden");
      // Set default dates
      $("#endDate").val(new Date().toISOString().split("T")[0]);
      $("#startDate").val(
        new Date(Date.now() - 30 * 24 * 60 * 60 * 1000)
          .toISOString()
          .split("T")[0]
      );
    } else {
      $("#customDateRange").addClass("hidden");
    }
  });

  // Apply Filter
  $("#btnApplyFilter").on("click", function () {
    applyChartFilters();
  });

  // Reset Filter
  $("#btnResetFilter").on("click", function () {
    $("#dateRangeFilter").val("30");
    $("#chartTypeFilter").val("all");
    $("#customDateRange").addClass("hidden");
    $("#filterSummary").addClass("hidden");
    location.reload(); // Reload page to show original data
  });

  function applyChartFilters() {
    const dateRange = $("#dateRangeFilter").val();
    const chartType = $("#chartTypeFilter").val();
    let startDate = "";
    let endDate = "";

    // Show loading
    $("#loadingIndicator").removeClass("hidden");

    // Get date parameters
    if (dateRange === "custom") {
      startDate = $("#startDate").val();
      endDate = $("#endDate").val();

      if (!startDate || !endDate) {
        showAlert("Please select both start and end dates", "error");
        $("#loadingIndicator").addClass("hidden");
        return;
      }
    }

    // Make AJAX request
    $.ajax({
      url: "index.php?controller=Admin&action=getFilteredReportData",
      method: "GET",
      data: {
        date_range: dateRange,
        start_date: startDate,
        end_date: endDate,
        chart_type: chartType,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          // Update charts with new data
          updateAllCharts(response.data, chartType);

          // Update KPIs
          updateKPIs(response.data);

          // Show filter summary
          const filterText =
            dateRange === "custom"
              ? `${response.data.filter_info.formatted_start} to ${response.data.filter_info.formatted_end}`
              : `Last ${dateRange} Days`;
          $("#filterSummaryText").text(filterText);
          $("#filterSummary").removeClass("hidden");

          showAlert("Charts updated successfully", "success");
        } else {
          showAlert("Error loading filtered data", "error");
        }
        $("#loadingIndicator").addClass("hidden");
      },
      error: function (xhr, status, error) {
        showAlert("Error: " + error, "error");
        $("#loadingIndicator").addClass("hidden");
      },
    });
  }

  function updateAllCharts(data, chartType) {
    // Update Revenue Trend Chart
    if (chartType === "all" || chartType === "revenue") {
      updateChart("revenueTrendChart", {
        labels: data.revenue_trend.map((d) => d.period_label),
        datasets: [
          {
            label: "Revenue (₱)",
            data: data.revenue_trend.map((d) => d.revenue),
            borderColor: "#3b82f6",
            backgroundColor: "rgba(59, 130, 246, 0.1)",
            fill: true,
            tension: 0.4,
            borderWidth: 2,
          },
        ],
      });
    }

    // Update Daily Revenue Chart
    if (chartType === "all" || chartType === "revenue") {
      updateChart("dailyRevenueChart", {
        labels: data.daily_revenue.map((d) => d.date_label),
        datasets: [
          {
            label: "Daily Revenue (₱)",
            data: data.daily_revenue.map((d) => d.revenue),
            backgroundColor: "rgba(34, 197, 94, 0.6)",
            borderColor: "#22c55e",
            borderWidth: 1,
            borderRadius: 4,
          },
        ],
      });
    }

    // Update Revenue by Plan Chart
    if (chartType === "all" || chartType === "revenue") {
      updateChart("revenueByPlanChart", {
        labels: data.revenue_by_plan.map((d) => d.plan_name),
        datasets: [
          {
            data: data.revenue_by_plan.map((d) => d.total_revenue),
            backgroundColor: [
              "rgba(59, 130, 246, 0.8)",
              "rgba(34, 197, 94, 0.8)",
              "rgba(251, 146, 60, 0.8)",
              "rgba(168, 85, 247, 0.8)",
              "rgba(236, 72, 153, 0.8)",
            ],
            borderWidth: 2,
            borderColor: "#1f2937",
          },
        ],
      });
    }

    // Update Member Growth Chart
    if (chartType === "all" || chartType === "members") {
      updateChart("memberGrowthChart", {
        labels: data.member_growth.map((d) => d.period_label),
        datasets: [
          {
            label: "New Members",
            data: data.member_growth.map((d) => d.new_members),
            borderColor: "#22c55e",
            backgroundColor: "rgba(34, 197, 94, 0.1)",
            fill: true,
            tension: 0.4,
            borderWidth: 2,
          },
        ],
      });
    }

    // Update Members by Plan Chart
    if (chartType === "all" || chartType === "members") {
      updateChart("membersByPlanChart", {
        labels: data.members_by_plan.map((d) => d.plan_name),
        datasets: [
          {
            label: "Members",
            data: data.members_by_plan.map((d) => d.member_count),
            backgroundColor: "rgba(168, 85, 247, 0.6)",
            borderColor: "#a855f7",
            borderWidth: 1,
            borderRadius: 4,
          },
        ],
      });
    }

    // Update Payment Method Chart
    if (chartType === "all" || chartType === "payments") {
      updateChart("paymentMethodChart", {
        labels: data.payment_method_stats.map(
          (d) => d.payment_method || "Unknown"
        ),
        datasets: [
          {
            data: data.payment_method_stats.map((d) => d.total_amount),
            backgroundColor: [
              "rgba(59, 130, 246, 0.8)",
              "rgba(34, 197, 94, 0.8)",
              "rgba(251, 146, 60, 0.8)",
              "rgba(236, 72, 153, 0.8)",
            ],
            borderWidth: 2,
            borderColor: "#1f2937",
          },
        ],
      });
    }
  }

  function updateChart(chartId, newData) {
    const canvas = document.getElementById(chartId);
    if (!canvas) return;

    // Get existing chart instance
    const existingChart = Chart.getChart(chartId);

    if (existingChart) {
      // Update existing chart
      existingChart.data = newData;
      existingChart.update("active");
    }
  }

  function updateKPIs(data) {
    // Update KPI values based on filtered data
    if (data.payment_stats) {
      // Update revenue KPIs
      // You can add specific selectors for your KPI cards here
    }
  }

  function showAlert(message, type) {
    const alertClass =
      type === "success"
        ? "bg-green-600"
        : type === "error"
        ? "bg-red-600"
        : "bg-blue-600";

    const alert = $(`
            <div class="alert ${alertClass} text-white px-6 py-4 rounded-lg shadow-lg mb-4 animate-fade-in">
                ${message}
            </div>
        `);

    $("#alertContainer").append(alert);

    setTimeout(() => {
      alert.fadeOut(300, function () {
        $(this).remove();
      });
    }, 3000);
  }
});
