  // Provided data (replace with your own)
  const labels1 = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];
  const dataset1Data = [6500, 5900, 8000, 8100, 7600, 8000, 10000];

  // Chart configuration
  const config1 = {
    type: 'line',
    data: {
      labels: labels1,
      datasets: [
        {
          label: 'Data',
          data: dataset1Data,
          borderColor: 'rgba(255, 99, 132, 1)',
          backgroundColor: 'rgba(255, 99, 132, 0.2)',
          tension: 0.4, // Adjust tension for smoothness (0 = straight lines, 1 = very smooth)
          fill: true
        },
      ]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: ''
        }
      },
      interaction: {
        intersect: false
      }
    }
  };

  // Create a new chart instance
  const ctx1 = document.getElementById('myChart1').getContext('2d');
  const myChart1 = new Chart(ctx1, config1);



  //2nd chart
    // Provided data (replace with your own)
    const labels2 = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];
    const dataset2Data = [6500, 5900, 8000, 8100, 7600, 8000, 10000];
  
    // Chart configuration
    const config2 = {
      type: 'line',
      data: {
        labels: labels2,
        datasets: [
          {
            label: 'Data',
            data: dataset2Data,
            borderColor: 'rgba(255, 99, 132, 1)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.4, // Adjust tension for smoothness (0 = straight lines, 1 = very smooth)
            fill: true
          },
        ]
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: ''
          }
        },
        interaction: {
          intersect: false
        }
      }
    };
  
    // Create a new chart instance
    const ctx2 = document.getElementById('myChart2').getContext('2d');
    const myChart2 = new Chart(ctx2, config2);




//vertical chart for ranking system
// Define your initial Chart.js configuration
const configD = {
  type: 'bar',
  data: {
      labels: [],
      datasets: [{
          label: 'Order Count',
          data: [],
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
  },
  options: {
      scales: {
          y: {
              beginAtZero: true
          }
      }
  }
};

document.addEventListener("DOMContentLoaded", function() {
  createDailyChart(); // Call createDailyChart function on page load
});

async function fetchData(url) {
  try {
      const response = await fetch(url);
      if (!response.ok) {
          throw new Error('Network response was not ok');
      }
      const data = await response.json();

      if (data.error) {
          throw new Error(data.error);
      }

      return data;

  } catch (error) {
      console.error('Error fetching data:', error);
      return null; // Return null if there's an error or no data
  }
}

async function createDailyChart() {
  const ctxD = document.getElementById('myChartD').getContext('2d');

  try {
      const dataD = await fetchData('../chartData/Ddata.php');

      if (!dataD || dataD.labels.length === 0) {
          // Handle case where no data is available
          const emptyConfigD = {
              type: 'bar',
              data: {
                  labels: [],
                  datasets: [{
                      label: 'Order Count',
                      data: [],
                      backgroundColor: [],
                      borderColor: [],
                      borderWidth: 1
                  }]
              },
              options: {
                  scales: {
                      y: {
                          beginAtZero: true
                      }
                  }
              }
          };

          new Chart(ctxD, emptyConfigD);
      } else {
          // Case where data is available
          configD.data.labels = dataD.labels;
          configD.data.datasets[0].data = dataD.datasets[0].data;

          new Chart(ctxD, configD);
      }

  } catch (error) {
      console.error('Error creating daily chart:', error);
  }
}
