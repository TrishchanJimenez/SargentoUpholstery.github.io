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
    document.getElementById(tabName).style.display = "block";
}



//Start for chart
        // Data for the charts (you can customize these datasets as needed)
        document.addEventListener("DOMContentLoaded", function() {
            // Data for the charts (you can customize these datasets as needed)
            const chartDataDaily = {
                labels: ['Red', 'Blue', 'Yellow'],
                datasets: [{
                    label: 'Daily Statistics',
                    data: [300, 50, 100],
                    backgroundColor: ['rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)'],
                    hoverOffset: 4
                }]
            };
        
            const chartDataWeekly = {
                labels: ['Red', 'Blue', 'Yellow'],
                datasets: [{
                    label: 'Weekly Statistics',
                    data: [200, 100, 150],
                    backgroundColor: ['rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)'],
                    hoverOffset: 4
                }]
            };
        
            const chartDataMonthly = {
                labels: ['Red', 'Blue', 'Yellow'],
                datasets: [{
                    label: 'Monthly Statistics',
                    data: [400, 75, 125],
                    backgroundColor: ['rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)'],
                    hoverOffset: 4
                }]
            };
        
            // Configuration for the charts
            const chartConfig = {
                type: 'doughnut',
                data: chartDataDaily,  // Default data, will be updated later
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom', // Adjust the position as needed
                            labels: {
                                font: {
                                    family: 'Inter', // Font family
                                    size: 16, // Font size
                                    style: 'normal', // Font style
                                    weight: 400, // Font weight
                                    lineHeight: 24 // Line height
                                },
                                color: 'rgb(26, 25, 25)' // Legend text color
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2);
                                }
                            }
                        }
                    }
                },
            };
        
            // Create charts for each tab content on page load
            var chartDailyStatus = new Chart(document.getElementById('chartDailyStatus'), {
                ...chartConfig,
                data: chartDataDaily,
            });
        
            var chartWeeklyStatus = new Chart(document.getElementById('chartWeeklyStatus'), {
                ...chartConfig,
                data: chartDataWeekly,
            });
        
            var chartMonthlyStatus = new Chart(document.getElementById('chartMonthlyStatus'), {
                ...chartConfig,
                data: chartDataMonthly,
            });
        
            var chartDailyType = new Chart(document.getElementById('chartDailyType'), {
                ...chartConfig,
                data: chartDataDaily,  // You can use different data here if needed
            });
        
            var chartWeeklyType = new Chart(document.getElementById('chartWeeklyType'), {
                ...chartConfig,
                data: chartDataWeekly, // You can use different data here if needed
            });
        
            var chartMonthlyType = new Chart(document.getElementById('chartMonthlyType'), {
                ...chartConfig,
                data: chartDataMonthly, // You can use different data here if needed
            });
        
            // Function to handle tab change and update charts
            function openstats(tabName) {
                var tabcontent = document.getElementsByClassName("tabcontent");
                for (var i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                document.getElementById(tabName).style.display = "block";
        
                // Update charts based on selected tab
                switch (tabName) {
                    case 'Daily':
                        chartDailyStatus.data = chartDataDaily;
                        chartDailyStatus.update();
                        chartDailyType.data = chartDataDaily;
                        chartDailyType.update();
                        break;
                    case 'Weekly':
                        chartWeeklyStatus.data = chartDataWeekly;
                        chartWeeklyStatus.update();
                        chartWeeklyType.data = chartDataWeekly;
                        chartWeeklyType.update();
                        break;
                    case 'Monthly':
                        chartMonthlyStatus.data = chartDataMonthly;
                        chartMonthlyStatus.update();
                        chartMonthlyType.data = chartDataMonthly;
                        chartMonthlyType.update();
                        break;
                    default:
                        break;
                }
            }
        
            // Event listener for tab selection
            var tabSelect = document.getElementById("tabSelect");
            tabSelect.addEventListener("change", function() {
                openstats(tabSelect.value);
            });
        
            // Trigger the default tab (Daily) on page load
            openstats(tabSelect.value);
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
        

//vertical chart for ranking system
const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];
const data = {
  labels: labels,
  datasets: [{
    label: 'My First Dataset',
    data: [65, 59, 80, 81, 56, 55, 40],
    backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(255, 159, 64, 0.2)',
      'rgba(255, 205, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(153, 102, 255, 0.2)',
      'rgba(201, 203, 207, 0.2)'
    ],
    borderColor: [
      'rgb(255, 99, 132)',
      'rgb(255, 159, 64)',
      'rgb(255, 205, 86)',
      'rgb(75, 192, 192)',
      'rgb(54, 162, 235)',
      'rgb(153, 102, 255)',
      'rgb(201, 203, 207)'
    ],
    borderWidth: 1
  }]
};

const config = {
  type: 'bar',
  data: data,
  options: {
    scales: {
      x: {
        beginAtZero: true
      }
    }
  },
};

// Initialize the chart
var myChart = new Chart(
    document.getElementById('myChart'),
    config
);




//start of line smoothen chart




  





