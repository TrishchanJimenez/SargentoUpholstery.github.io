const chartConfig = {
	type: "doughnut",
	data: {
		labels: [],
		datasets: [
		{
			label: "Status",
			data: [],
			backgroundColor: [
			"rgba(255, 99, 132, 0.2)",
			"rgba(54, 162, 235, 0.2)",
			"rgba(255, 205, 86, 0.2)",
			"rgba(75, 192, 192, 0.2)",
			"rgba(153, 102, 255, 0.2)",
			"rgba(255, 159, 64, 0.2)",
			"rgba(201, 203, 207, 0.2)",
			],
			borderColor: [
			"rgb(255, 99, 132)",
			"rgb(54, 162, 235)",
			"rgb(255, 205, 86)",
			"rgb(75, 192, 192)",
			"rgb(153, 102, 255)",
			"rgb(255, 159, 64)",
			"rgb(201, 203, 207)",
			],
			borderWidth: 1,
		},
		],
	},
	options: {
		responsive: true,
		aspectRatio: 1.75,
		plugins: {
			legend: {
				display: true,
				position: "bottom",
				labels: {
				font: {
					family: "Inter",
					size: 16,
					style: "normal",
					weight: 400,
					lineHeight: 24,
				},
				color: "rgb(26, 25, 25)",
				},
			},
			tooltip: {
				callbacks: {
					label: function (tooltipItem) {
						return tooltipItem.label + ": " + tooltipItem.raw;
					},
				},
			},
		},
	}
};

// Chart configuration
const barchartConfig = {
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
				beginAtZero: true,
				ticks: {
					stepSize: 1
				}
			}
		}
	}
};

const chartOrderStatus = new Chart(document.getElementById('statusChart'), chartConfig);
const chartOrderType = new Chart(document.getElementById('typeChart'), chartConfig);
const chartFurnitureType = new Chart(document.getElementById('furnitureChart'), barchartConfig);
        
function fetchStatusData(chart) {
    fetch(`../chartData/order_status_data.php`)
        .then(response => {
			// console.log(response.text());
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

function fetchTypeData(chart) {
    fetch(`../chartData/order_type_data.php`)
        .then(response => {
			// console.log(response.text());
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

function fetchFurnitureData(chart) {
	// Fetch data from PHP script
	fetch('/chartData/furniture_type_data.php')
		.then(response => {
			if (!response.ok) {
				throw new Error('Network response was not ok  ');
			}
			return response.json();
		})
		.then(data => {
			// Update chart data
			chart.data.labels = data.labels;
			chart.data.datasets[0].data = data.datasets[0].data;
			chart.update();
		})
		.catch(error => {
			console.error('Error fetching data:', error);
			// Handle and display error in UI if necessary
		});
}

fetchStatusData(chartOrderStatus);
fetchTypeData(chartOrderType);
fetchFurnitureData(chartFurnitureType);