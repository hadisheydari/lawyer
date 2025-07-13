import ApexCharts from 'apexcharts'

document.addEventListener('DOMContentLoaded', function () {

    const sparklineData = [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54];

    function randomizeArray(arr) {
        return arr
            .map(value => ({value, sort: Math.random()}))
            .sort((a, b) => a.sort - b.sort)
            .map(({value}) => value);
    }

    // تابع تنظیمات اسپارک‌لاین‌ها
    const sparkOptions = (color, data) => ({
        series: [{data}],
        chart: {
            type: 'area',
            height: 60,
            sparkline: {enabled: true}
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        fill: {
            opacity: 0.3
        },
        colors: [color],
        tooltip: {
            enabled: false
        }
    });

    // ساخت اسپارک‌لاین‌ها
    new ApexCharts(document.querySelector("#spark1"), sparkOptions("#10B981", randomizeArray(sparklineData))).render();
    new ApexCharts(document.querySelector("#spark2"), sparkOptions("#3B82F6", randomizeArray(sparklineData))).render();
    new ApexCharts(document.querySelector("#spark3"), sparkOptions("#F59E0B", randomizeArray(sparklineData))).render();

    const salesChartOptions = {
        series: [{
            name: 'تحویل بار',
            data: [144, 155, 141, 167, 122, 143, 121, 133, 145, 131, 117, 165]
        }],
        annotations: {
            points: [{
                x: 'تیر',
                seriesIndex: 0,
                label: {
                    borderColor: '#775DD0',
                    offsetY: 0,
                    style: {
                        color: '#fff',
                        background: '#775DD0',
                    },
                    text: 'عملکرد خوب در تیر ماه',
                }
            }]
        },
        chart: {
            height: 350,
            type: 'bar',
        },
        plotOptions: {
            bar: {
                borderRadius: 10,
                columnWidth: '50%',
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 0
        },
        grid: {
            row: {
                colors: ['#fff', '#f2f2f2']
            }
        },
        xaxis: {
            labels: {
                rotate: -45
            },
            categories: [
                'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
                'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
            ],
            tickPlacement: 'on'
        },
        yaxis: {
            title: {
                text: 'تعداد تحویل بار ',
            },
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: "horizontal",
                shadeIntensity: 0.25,
                gradientToColors: undefined,
                inverseColors: true,
                opacityFrom: 0.85,
                opacityTo: 0.85,
                stops: [50, 0, 100]
            },
        }
    };

    new ApexCharts(document.querySelector("#chart"), salesChartOptions).render();


    var polarAreaOptions = {
        series: [30, 25, 20],
        labels: ['ازاد', 'رزرو', 'rfq' ],
        chart: {
            type: 'polarArea',
            height: 350,
        },
        stroke: {
            colors: ['#fff']
        },
        fill: {
            opacity: 0.8
        },

        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 280
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };


    var chart = new ApexCharts(document.querySelector("#polarAreaChart"), polarAreaOptions);
    chart.render();


});
