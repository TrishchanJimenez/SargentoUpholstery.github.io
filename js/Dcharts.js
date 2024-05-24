  // Provided data (replace with your own)
  const labels1 = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];
  const dataset1Data = [6500, 5900, 8000, 8100, 7600, 8000, 10000];

  // Chart configuration
  document.addEventListener("DOMContentLoaded", function() {
    // Chart configuration for myChart1 (daily line chart)
    const config1 = {
        type: 'line',
        data: {
            labels: [], // Labels will be populated with fetched data
            datasets: [
                {
                    label: 'Daily Average Quoted Price',
                    data: [], // Data will be populated with fetched data
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.4, // Adjust tension for smoothness (0 = straight lines, 1 = very smooth)
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Daily Average Quoted Price'
                }
            },
            interaction: {
                intersect: false
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Average Quoted Price'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Placement Day'
                    }
                }
            }
        }
    };
  
    // Chart configuration for myChart2 (weekly line chart)
    const config2 = {
        type: 'line',
        data: {
            labels: [], // Labels will be populated with fetched data
            datasets: [
                {
                    label: 'Weekly Average Quoted Price',
                    data: [], // Data will be populated with fetched data
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Weekly Average Quoted Price'
                }
            },
            interaction: {
                intersect: false
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Average Quoted Price'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Placement Week'
                    }
                }
            }
        }
    };
  
    // Chart configuration for myChart3 (monthly line chart)
    const config3 = {
        type: 'line',
        data: {
            labels: [], // Labels will be populated with fetched data
            datasets: [
                {
                    label: 'Monthly Average Quoted Price',
                    data: [], // Data will be populated with fetched data
                    borderColor: 'rgba(255, 205, 86, 1)',
                    backgroundColor: 'rgba(255, 205, 86, 0.2)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Average Quoted Price'
                }
            },
            interaction: {
                intersect: false
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Average Quoted Price'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Placement Month'
                    }
                }
            }
        }
    };
  
    // Fetch data from linegraphdata.php for all charts
    fetch('/chartData/linegraphdata.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Check if there is data returned for daily chart (myChart1)
            if (data.daily.labels.length > 0) {
                config1.data.labels = data.daily.labels;
                config1.data.datasets[0].data = data.daily.datasets[0].data;
            }
  
            // Check if there is data returned for weekly chart (myChart2)
            if (data.weekly.labels.length > 0) {
                config2.data.labels = data.weekly.labels;
                config2.data.datasets[0].data = data.weekly.datasets[0].data;
            }
  
            // Check if there is data returned for monthly chart (myChart3)
            if (data.monthly.labels.length > 0) {
                config3.data.labels = data.monthly.labels;
                config3.data.datasets[0].data = data.monthly.datasets[0].data;
            }
  
            // Create myChart1 instance
            const ctx1 = document.getElementById('myChart1').getContext('2d');
            const myChart1 = new Chart(ctx1, config1);
  
            // Create myChart2 instance
            const ctx2 = document.getElementById('myChart3').getContext('2d');
            const myChart3 = new Chart(ctx2, config2);
  
            // Create myChart3 instance
            const ctx3 = document.getElementById('myChart5').getContext('2d');
            const myChart5 = new Chart(ctx3, config3);
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            // Handle and display error in UI if necessary
        });
  });
  





//vertical chart for ranking system
// Define your initial Chart.js configuration
document.addEventListener("DOMContentLoaded", function() {
  // Chart configuration
  const configD = {
      type: 'bar',
      data: {
          labels: [], // Labels will be populated with fetched data
          datasets: [
              {
                  label: 'Order Count',
                  data: [], // Data will be populated with fetched data
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
              }
          ]
      },
      options: {
          responsive: true,
          plugins: {
              legend: {
                  position: 'top',
              } 
          },
          scales: {
              y: {
                  beginAtZero: true
              }
          }
      }
  };

  // Fetch data from PHP script
  fetch('/chartData/Ddata.php')
      .then(response => {
          if (!response.ok) {
              throw new Error('Network response was not ok  ');
          }
          return response.json();
      })
      .then(data => {
          // Update chart data
          configD.data.labels = data.labels;
          configD.data.datasets[0].data = data.datasets[0].data;

          // Create chart instance
          const ctxD = document.getElementById('myChartD').getContext('2d');
          const myChartD = new Chart(ctxD, configD);
      })
      .catch(error => {
          console.error('Error fetching data:', error);
          // Handle and display error in UI if necessary
      });
});


