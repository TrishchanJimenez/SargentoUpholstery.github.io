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
  const ctx = document.getElementById('myChart1').getContext('2d');
  const myChart1 = new Chart(ctx, config1);