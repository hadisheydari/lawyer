(function ($) {

	var icChartlist = function () {

		var screenWidth = $(window).width();

		var handleWeeklySales = function () {
			var options = {
				series: [
					{
						name: 'Sales',
						data: [28, 40, 50, 68, 30, 68, 48, 28, 20]
					},
				],
				labels: ['1st Week', '2st Week', '3st Week', '4st Week', '5st Week', '6st Week', '7st Week', '8st Week', '9st Week'],
				chart: {
					type: 'bar',
					stacked: false,
					height: 150,
					offsetX: -8,
					offsetY: 25,

					toolbar: {
						show: false,
					}
				},
				plotOptions: {
					bar: {
						horizontal: false,
						borderRadius: 2,
						columnWidth: '30%',
						colors: {
							backgroundBarOpacity: 1,
						},
					},
				},
				colors: ['var(--primary)', '#000'],
				xaxis: {
					show: false,
					axisBorder: {
						show: false,
					},
					axisTicks: {
						show: false,
					},
					labels: {
						show: false,
						style: {
							colors: '#828282',
							fontSize: '14px',
							fontFamily: 'Poppins',
							fontWeight: 'light',
							cssClass: 'apexcharts-xaxis-label',
						},
					},
					crosshairs: {
						show: false,
					},
				},
				yaxis: {
					labels: {
						show: false,
					},
				},
				grid: {
					show: false,
				},
				toolbar: {
					enabled: false,
				},
				dataLabels: {
					enabled: false
				},
				legend: {
					show: false
				},
				fill: {
					opacity: 1
				}
			};
			var chart = new ApexCharts(document.querySelector("#handleWeeklySales"), options);
			chart.render();
		}

		var handleOrderChart = function () {
			var options = {
				series: [
					{
						name: 'Net Profit',
						data: [200, 190, 200, 190, 205, 185, 200, 190],
					},
				],
				chart: {
					type: 'area',
					height: 100,
					toolbar: {
						show: false,
					},
					zoom: {
						enabled: false
					},
					sparkline: {
						enabled: true
					}
				},
				colors: ['var(--secondary)'],
				dataLabels: {
					enabled: false,
				},
				legend: {
					show: true,
				},
				stroke: {
					show: true,
					width: 2,
					curve: 'smooth',
					colors: ['var(--secondary)'],
				},
				grid: {
					show: false,
					borderColor: '#eee',
					padding: {
						top: 0,
						right: 0,
						bottom: 0,
						left: -1
					}
				},
				states: {
					normal: {
						filter: {
							type: 'none',
							value: 0
						}
					},
					hover: {
						filter: {
							type: 'none',
							value: 0
						}
					},
					active: {
						allowMultipleDataPointsSelection: false,
						filter: {
							type: 'none',
							value: 0
						}
					}
				},
				xaxis: {
					categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'August'],
					axisBorder: {
						show: true,
					},
					axisTicks: {
						show: true
					},
					labels: {
						show: true,
						style: {
							fontSize: '8px',
						}
					},
					crosshairs: {
						show: true,
						position: 'front',
						stroke: {
							width: 0,
							dashArray: 3
						}
					},
					tooltip: {
						enabled: false,
					}
				},
				yaxis: {
					show: false,
				},
				fill: {
					opacity: 0.9,
					colors: 'var(--secondary)',
					type: 'gradient',
					gradient: {
						colorStops: [
							{
								offset: 0,
								color: 'var(--secondary)',
								opacity: .30,
							},
							{
								offset: 0.6,
								color: 'var(--secondary)',
								opacity: .15,
							},
							{
								offset: 100,
								color: 'white',
								opacity: 0,
							}
						],
					}
				},
			};

			var chartBar1 = new ApexCharts(document.querySelector("#handleOrderChart"), options);
			chartBar1.render();
		}
		var redial = function () {
			var options = {
				series: [75],
				chart: {
					type: 'radialBar',
					offsetY: 0,
					height: 160,
					sparkline: {
						enabled: true
					}
				},
				plotOptions: {
					radialBar: {
						startAngle: -180,
						endAngle: 180,
						track: {
							background: "#F1EAFF",
							strokeWidth: '100%',
							margin: 3,
						},

						hollow: {
							margin: 20,
							size: '60%',
							background: 'transparent',
							image: undefined,
							imageOffsetX: 0,
							imageOffsetY: 0,
							position: 'front',
						},

						dataLabels: {
							name: {
								show: false
							},
							value: {
								offsetY: 5,
								fontSize: '24px',
								color: '#000000',
								fontWeight: 600,
							}
						}
					}
				},
				responsive: [{
					breakpoint: 1600,
					options: {
						chart: {
							height: 150
						},
					}
				}

				],
				grid: {
					padding: {
						top: -10
					}
				},
				/* stroke: {
				  dashArray: 4,
				  colors:'#6EC51E'
				}, */
				fill: {
					type: 'gradient',
					colors: '#7A849B',
					gradient: {
						shade: 'black',
						shadeIntensity: 0.15,
						inverseColors: false,
						opacityFrom: 1,
						opacityTo: 1,
						stops: [64, 43, 64, 0.5]
					},
				},
				labels: ['Average Results'],
			};

			var chart = new ApexCharts(document.querySelector("#redial"), options);
			chart.render();


		}


		var blanceChart = function () {
			var options = {
				series: [
					{
						name: "2024",
						data: [1.2, 2.7, 1, 3.6, 2.1, 2.7, 2.2,],
					},
					{
						name: "2023",
						data: [-2.8, -1.1, -2.5, -1.5, -2.3, -1.9, -1,],
					},
				],
				chart: {
					toolbar: {
						show: false,
					},
					type: "bar",
					fontFamily: "inherit",
					foreColor: "#adb0bb",
					height: 270,
					stacked: true,
					offsetX: -15,
				},
				colors: ["var(--bs-primary)", "#01bd9b", ],
				plotOptions: {
					bar: {
						horizontal: false,
						barHeight: "60%",
						columnWidth: "20%",
						borderRadius: [5],
						borderRadiusApplication: "start",
						borderRadiusWhenStacked: "all",
					},
				},
				dataLabels: {
					enabled: false,
				  },
				  legend: {
					show: false,
				  },
				  grid: {
					show: true,
					padding: {
					  top: 0,
					  bottom: 0,
					  right: 0,
					},
					borderColor: "rgba(0,0,0,0.05)",
					xaxis: {
					  lines: {
						show: true,
					  },
					},
					yaxis: {
					  lines: {
						show: true,
					  },
					},
				  },
				  yaxis: {
					min: -5,
					max: 5,
				  },
				  xaxis: {
					axisBorder: {
					  show: false,
					},
					axisTicks: {
					  show: false,
					},
					categories: [
					  "Sun",
					  "Mon",
					  "Tue",
					  "Wed",
					  "Thu",
					  "Fri",
					  "Sat",
					],
					labels: {
					  style: { fontSize: "13px", colors: "#adb0bb", fontWeight: "400" },
					},
				  },
				  yaxis: {
					tickAmount: 4,
				  },
				  tooltip: {
					theme: "dark",
				  },
			};
			

			var chart = new ApexCharts(document.querySelector("#blanceChart"), options);
			chart.render();

		}

		var handleMarketShare = function () {
			var options = {
				series: [12, 4, 4],
				chart: {
					type: 'donut',
					height: 135,
					width: 135,
				},
				labels: ["Mobile", "Laptop", "Cloths"],
				plotOptions: {
					pie: {
						donut: {
							size: '85%',
							labels: {
								show: true,
								name: {
									show: false,
									offsetY: 12,
								},
								value: {
									show: true,
									fontSize: '20px',
									fontWeight: '600',
									offsetY: 8,

									formatter: function (e) {
										return parseInt(e) + "M"
									}
								},
								total: {
									show: true,
									fontSize: '20px',
									fontWeight: '600',
									offsetY: -100,
									label: "",

									formatter: function (w) {
										var total = w.globals.seriesTotals.reduce((a, b) => {
											return a + b
										}, 0);
										return total + "M";
									}
								},
							}
						}
					}
				},
				dataLabels: {
					enabled: false,
				},
				stroke: {
					width: 2,
				},
				colors: ['#1C2430', '#01BD9B', '#738293'],
				legend: {
					position: 'bottom',
					show: false
				},
				tooltip: {
					enabled: false,
				},
				states: {
					hover: {
						filter: {
							type: "none"
						}
					},
					active: {
						filter: {
							type: "none"
						}
					}
				},
			};

			var chart = new ApexCharts(document.querySelector("#handleMarketShare"), options);
			chart.render();
		}


		var chartBarRunning = function () {
			var options = {
				series: [
					{
						name: 'Revenue',
						data: [31, 40, 28, 25, 40, 28, 31, 40, 28, 31, 40, 28]
					},
					{
						name: 'Active Projects',
						data: [11, 32, 45, 38, 25, 20, 36, 45, 15, 11, 32, 45]
					},

				],
				chart: {
					type: 'bar',
					height: 300,


					toolbar: {
						show: false,
					},

				},
				plotOptions: {
					bar: {
						horizontal: false,
						endingShape: 'rounded',
						columnWidth: '45%',
						borderRadius: 3,

					},
				},
				colors: ['#0074FF', '#01BD9B'],
				dataLabels: {
					enabled: false,
				},

				legend: {
					show: true,
					fontSize: '13px',
					labels: {
						colors: '#888888',

					},
					markers: {
						width: 10,
						height: 10,
						strokeWidth: 0,
						strokeColor: '#fff',
						fillColors: ['var(--primary)', '#01BD9B'],
						radius: 30,
					}
				},
				stroke: {
					show: true,
					width: 6,
					colors: ['transparent']
				},
				grid: {
					show: true,
					borderColor: '#EEF0F7',
					strokeDashArray: 3,
					xaxis: {
						lines: {
							show: false
						}
					},
					yaxis: {
						lines: {
							show: true
						}
					},
				},
				xaxis: {
					categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
					labels: {
						style: {
							colors: ['#1C2430'],
							fontSize: '13px',
							fontFamily: 'poppins',
							fontWeight: 100,
							cssClass: 'apexcharts-xaxis-label',
						},
					},
					axisBorder: {
						show: false,
					},
					axisTicks: {
						show: false,
						borderType: 'solid',
						color: '#1C2430',
						height: 6,
						offsetX: 0,
						offsetY: 0
					},
					crosshairs: {
						show: true,
					}
				},
				yaxis: {
					labels: {
						offsetX: -16,
						style: {
							colors: ['#1C2430'],
							fontSize: '15px',
							fontFamily: 'poppins',
							fontWeight: 100,
							cssClass: 'apexcharts-xaxis-label',
						},
					},
				},
				fill: {
					opacity: 1,
					colors: ['var(--primary)', '#01BD9B'],
				},
				tooltip: {
					y: {
						formatter: function (val) {
							return "$ " + val + ""
						}
					}
				},
				responsive: [{
					breakpoint: 575,
					options: {
						plotOptions: {
							bar: {
								columnWidth: '1%',
								borderRadius: -1,
							},
						},
						chart: {
							height: 250,
						},
						series: [
							{
								name: 'Projects',
								data: [31, 40, 28, 31, 40, 28, 31, 40]
							},
							{
								name: 'Projects',
								data: [11, 32, 45, 31, 40, 28, 31, 40]
							},

						],
					}
				}]
			};

			if (jQuery("#chartBarRunning").length > 0) {

				var chart = new ApexCharts(document.querySelector("#chartBarRunning"), options);
				chart.render();

				jQuery('#dzIncomeSeries').on('change', function () {
					jQuery(this).toggleClass('disabled');
					chart.toggleSeries('Income');
				});

				jQuery('#dzExpenseSeries').on('change', function () {
					jQuery(this).toggleClass('disabled');
					chart.toggleSeries('Expense');
				});

			}

		}
		var handleWorldMap = function (trigger = 'load') {
			var vmapSelector = $('#world-map');
			if (trigger == 'resize') {
				vmapSelector.empty();
				vmapSelector.removeAttr('style');
			}

			vmapSelector.delay(500).unbind().vectorMap({
				map: 'world_en',
				backgroundColor: 'transparent',
				borderColor: 'rgb(239, 242, 244)',
				borderOpacity: 0.25,
				borderWidth: 1,
				color: 'rgb(233, 243, 255)',
				enableZoom: true,
				hoverColor: 'rgba(0, 166, 255, 1)',
				hoverOpacity: null,
				normalizeFunction: 'linear',
				scaleColors: ['#b6d6ff', '#005ace'],
				selectedColor: 'rgb(0, 116, 255 1)',
				selectedRegions: null,
				showTooltip: true,
				onRegionClick: function (element, code, region) {
					var message = 'You clicked "'
						+ region
						+ '" which has the code: '
						+ code.toUpperCase();

					alert(message);
				}
			});
		}


		/* Function ============ */
		return {
			init: function () {
			},


			load: function () {
				handleWeeklySales();
				handleOrderChart();
				handleMarketShare();
				redial();
				blanceChart();
				chartBarRunning();
				handleWorldMap();

			},

			resize: function () {
			}
		}

	}();



	jQuery(window).on('load', function () {
		setTimeout(function () {
			icChartlist.load();
		}, 1000);

	});



})(jQuery);