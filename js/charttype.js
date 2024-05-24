document.addEventListener("DOMContentLoaded", function() {
    const chartConfig= {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                label: 'Status',
                data: [],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(201, 203, 207, 0.2)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)',
                    'rgb(255, 159, 64)',
                    'rgb(201, 203, 207)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        font: {
                            family: 'Inter',
                            size: 16,
                            style: 'normal',
                            weight: 400,
                            lineHeight: 24
                        },
                        color: 'rgb(26, 25, 25)'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(0); // Displaying without decimals
                        }
                    }
                }
            }
        }
    };

    const chartDailytype = new Chart(document.getElementById('chartDailytype'), chartConfig);
    const chartWeeklytype = new Chart(document.getElementById('chartWeeklytype'), chartConfig);
    const chartMonthlytype = new Chart(document.getElementById('chartMonthlytype'), chartConfig);

    function fetchData(interval, chart) {
        fetch(`/chartData/doughnuttype.php?interval=${interval}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                chart.data.labels = data.labels;
                chart.data.datasets[0].data = data.datasets[0].data;
                chart.update();
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }

    // Event listener for tab selection
    const tabSelect = document.getElementById('tabSelect');
    tabSelect.addEventListener('change', function() {
        const selectedInterval = tabSelect.value;
        switch (selectedInterval) {
            case 'Daily':
                fetchData('Daily', chartDailytype);
                break;
            case 'Weekly':
                fetchData('Weekly', chartWeeklytype);
                break;
            case 'Monthly':
                fetchData('Monthly', chartMonthlytype);
                break;
            default:
                break;
        }
    });

    // Initial fetch for default tab (Daily)
    fetchData('Daily', chartDailytype);
});