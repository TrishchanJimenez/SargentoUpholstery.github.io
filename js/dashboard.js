document.addEventListener("DOMContentLoaded", function() {
    var tabSelect = document.getElementById("tabSelect");
    tabSelect.addEventListener("change", function() {
        openstats(tabSelect.value);
    });

    // Trigger the default tab (Daily) on page load
    openstats(tabSelect.value);
});

function openstats(tabName) {
    var i, tabcontent;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    document.getElementById(tabName).style.display = "flex";
}



//Start for chart
        // Data for the charts (you can customize these datasets as needed)
        document.addEventListener("DOMContentLoaded", function() {
            const chartConfig = {
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
                                    return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                                }
                            }
                        }
                    }
                }
            };
        
            const chartDaily = new Chart(document.getElementById('chartDaily'), chartConfig);
            const chartWeekly = new Chart(document.getElementById('chartWeekly'), chartConfig);
            const chartMonthly = new Chart(document.getElementById('chartMonthly'), chartConfig);
        
            function fetchData(interval, chart) {
                fetch(`../chartData/doughnut.php?interval=${interval}`)
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
                        fetchData('Daily', chartDaily);
                        break;
                    case 'Weekly':
                        fetchData('Weekly', chartWeekly);
                        break;
                    case 'Monthly':
                        fetchData('Monthly', chartMonthly);
                        break;
                    default:
                        break;
                }
            });
        
            // Initial fetch for default tab (Daily)
            fetchData('Daily', chartDaily);
        });
        
        


//datetime
window.onload = function() {
    setInterval(function(){
        var date = new Date();
        
        var optionsDate = {
            weekday: 'short',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };

        var optionsTime = {
            hour: 'numeric',
            minute: 'numeric',
            hour12: true
        };

        var displayDate = date.toLocaleDateString('en-US', optionsDate);
        var displayTime = date.toLocaleTimeString('en-US', optionsTime);

        document.getElementById('datetime').innerHTML = displayDate + " " + displayTime.toLowerCase();
    }, 1000); // 1000 milliseconds = 1 second
}
//end of datetime
        














