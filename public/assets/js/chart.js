$(document).ready(function() {

	// Donut Chart
	
	Morris.Donut({
		element: 'donut-chart',
		data: [
		  { label: "Male", value: 25 },
		  { label: "Female", value: 37 },
		  { label: "Not assigned", value: 10 },
		],
		colors: ['#002366','#ffdc00', '#007bff']
	  });
	
	// Bar Chart
	
	Morris.Bar({
		element: 'bar-charts',
		data: [
		  { ageGroup: '18-25', count: 30 },
		  { ageGroup: '26-33', count: 55 },
		  { ageGroup: '34-41', count: 40 },
		  { ageGroup: '42-49', count: 20 },
		  { ageGroup: '50-57', count: 15 },
		  { ageGroup: '58-65', count: 10 },
		  { ageGroup: '66+', count: 5 },
		],
		xkey: 'ageGroup',
		ykeys: ['count'],
		labels: ['Number of People'],
		barColors: ['#002366']
	  });
		
});