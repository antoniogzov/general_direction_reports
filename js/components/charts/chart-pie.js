'use strict';

var PieChart = (function () {
	// Variables
	var $chart = $('#chart-pie');
	// Methods
	function init($this) {
		var randomScalingFactor = function () {
			return Math.round(Math.random() * 100);
		};
		var pieChart = new Chart($this, {
			type: 'pie',
			data: {
				labels: [
					'Porcentaje registrado',
					'Porcentaje restante'
				],
				datasets: [{
					data: [
						randomScalingFactor(),
						randomScalingFactor(),
					],
					backgroundColor: [
						Charts.colors.theme['success'],
						Charts.colors.theme['secondary']
					],
					label: 'Dataset 1'
				}],
			},
			options: {
				responsive: true,
				legend: {
					position: 'top',
				},
				animation: {
					animateScale: true,
					animateRotate: true
				}
			}
		});

		// Save to jQuery object

		$this.data('chart', pieChart);
	};
	// Events
	if ($chart.length) {
		init($chart);
	}

})();
