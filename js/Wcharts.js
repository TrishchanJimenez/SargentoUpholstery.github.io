    //vertical chart for ranking system
    const configW = {
      type: 'bar',
      data: {
          labels: [],
          datasets: [{
              label: 'Weekly Orders',
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
      createWeeklyChart(); // Call createWeeklyChart function on page load
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
  
  async function createWeeklyChart() {
      const ctxW = document.getElementById('myChartW').getContext('2d');
  
      try {
          const dataW = await fetchData('../chartData/Wdata.php');
  
          if (!dataW || dataW.labels.length === 0) {
              // Handle case where no data is available
              const emptyConfigW = {
                  type: 'bar',
                  data: {
                      labels: [],
                      datasets: [{
                          label: 'Weekly Orders',
                          data: [],
                          backgroundColor: 'rgba(54, 162, 235, 0.2)',
                          borderColor: 'rgb(54, 162, 235)',
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
  
              new Chart(ctxW, emptyConfigW);
          } else {
              // Case where data is available
              configW.data.labels = dataW.labels;
              configW.data.datasets[0].data = dataW.values;
  
              new Chart(ctxW, configW);
          }
  
      } catch (error) {
          console.error('Error creating weekly chart:', error);
      }
  }
  