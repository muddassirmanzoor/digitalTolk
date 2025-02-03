jQuery(function ($) {
    $('#totalTeacherQualifiedNotQualified').highcharts({
        chart: {
            type: 'pie',
            custom: {},
            events: {
                render() {
                    const chart = this,
                        series = chart.series[0];
                    let customLabel = chart.options.chart.custom.label;

                    if (!customLabel) {
                        customLabel = chart.options.chart.custom.label =
                            chart.renderer.label(
                                'Total<br/>' +
                                '<strong>100%</strong>'
                            )
                                .css({
                                    color: '#000',
                                    textAnchor: 'middle'
                                })
                                .add();
                    }

                    const x = series.center[0] + chart.plotLeft,
                        y = series.center[1] + chart.plotTop -
                            (customLabel.attr('height') / 2);

                    customLabel.attr({
                        x,
                        y
                    });
                    // Set font size based on chart diameter
                    customLabel.css({
                        fontSize: `${series.center[2] / 12}px`
                    });
                }
            }
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        title: {
            text: 'Teacher by Marks',
            align: 'left',
            style: {
                display: 'none'
            }
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage}</b>'
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: [{
                    enabled: true,
                    distance: 20,
                    format: '{point.name}'
                }, {
                    enabled: true,
                    distance: -15,
                    format: '{point.y}%',
                    style: {
                        fontSize: '0.9em'
                    }
                }],
                showInLegend: true
            }
        },
        series: [{
            name: 'Total Teachers',
            colorByPoint: true,
            innerSize: '60%',
            data: [{
                name: 'Female',
                y: 60,
                color: '#fa5c7c91'
            }, {
                name: 'Male',
                y: 40,
                color: '#24788b91'
            }]
        }]
    });
    $('#TeacherMarks').highcharts({

        chart: {
            type: 'column'
        },
        title: {
            text: 'Teacher Types Vs Qualified & Not Qualified',
            align: 'left',
            style: {
                display: 'none'
            }
        },
        xAxis: {
            categories: ['PST','ESE', 'EST', 'SESE', 'SST', 'SSE','HM', 'Principal', 'SSS', 'SS']
        },

        yAxis: {
            allowDecimals: false,
            min: 0,
            title: {
                text: '#Number Of Teachers'
            }
        },

        tooltip: {
            format: '<b>{point.key}</b><br/>{series.name}: {y}<br/>' +
                'Total: {point.percentage}'
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold'
                    },
                    formatter: function() {
                        return this.y;  // Show the number on top of the bar
                    }
                }
            }
        },

        series: [{
            name: 'Male Marks',
            data: [113, 122, 95, 148, 133, 113, 122, 95, 148, 133],
            color: '#4a99a4'
        },{
            name: 'Female Marks',
            data: [102, 98, 65, 148, 133, 113, 122, 95, 148, 133],
            color: '#fb869f'
        }]
    });
    $('#DistrictWiseSubjectPerformanceHeatmap').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Teacher Type',
            align: 'center', // Horizontal alignment (center by default)
            verticalAlign: 'bottom', // Move title to the bottom
            y: 10, // Optional: Adjust vertical position (move it up slightly if needed)
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        },
        xAxis: {
            type: 'category',
            labels: {
                autoRotation: [-45, -90],
                style: {
                    fontSize: '13px',
                    fontFamily: 'Nunito, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Number Of Teacher'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Subject Wise Teacher Ranking: <b>{point.y:.1f}%</b>'
        },
        plotOptions: {
            column: {
                pointWidth: 30, // Set fixed bar width (in pixels)
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    inside: true,
                    verticalAlign: 'top',
                    format: '{point.y:.1f}%',
                    y: 10,
                    style: {
                        fontSize: '13px'
                    }
                }
            }
        },
        series: [{
            name: 'Population',
            colors: ['#37b067', '#6296bc', '#edb40d', '#7fd7c1', '#9f8cae', '#eb6672',
                '#376c72', '#ee9dcc', '#e3791a', '#9f765e', '#5f5c3b'],
            colorByPoint: true,
            groupPadding: 0,
            data: [
                ['Content', 37.33],
                ['Pedagogy', 31.18],
                ['Cognitive', 27.79]
                /*['Science', 22.23],
                ['Islam', 21.91],
                ['GK Social Study', 21.74],
                ['Computer Science', 21.32],
                ['Arabic', 20.89],
                ['Physical Education', 20.67],
                ['Drawing', 19.11],
                ['Pedagogy', 16.45]*/
            ],
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                inside: true,
                verticalAlign: 'top',
                format: '{point.y:.1f} %', // one decimal
                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '13px'
                }
            }
        }]
    });
    $('#districtsQualifiedNotQualified').highcharts({

        chart: {
            type: 'column'
        },
        title: {
            text: 'Districts Wise Female & Male Teachers',
            align: 'left',
            style: {
                display: 'none'
            }
        },
        xAxis: {
            categories: ['Bahawalpur', 'Bahawalnagar', 'Rahim Yar Khan','Gujranwala', 'Gujrat', 'Gujrat','Hafizabad', 'Mandi Bahauddin', 'Narowal', 'Sialkot', 'Rawalpindi', 'Jhelum', 'Chakwal', 'Attock', 'Dera Ghazi Khan', 'Layyah', 'Muzaffargarh', 'Rajanpur', 'Lahore', 'Kasur', 'Nankana Sahib', 'Sheikhupura', 'Sahiwal', 'Pakpattan', 'Okara','Faisalabad', 'Chiniot', 'Toba Tek Singh', 'Jhang', 'Multan', 'Lodhran', 'Khanewal', 'Vehari', 'Sargodha', 'Khushab', 'Mianwali', 'Bhakkar' ]
        },

        yAxis: {
            allowDecimals: false,
            min: 0,
            title: {
                text: '#Number Of Teachers'
            }
        },

        tooltip: {
            format: '<b>{point.key}</b><br/>{series.name}: {y}'
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold'
                    },
                    formatter: function() {
                        return this.y;  // Show the number on top of the bar
                    }
                }
            }
        },

        series: [{
            name: 'Female Marks',
            data: [102, 98, 65, 148, 133,102, 98, 65, 148, 133,102, 98, 65, 148, 133,102, 98, 65, 148, 133,102, 98, 65, 148, 133,102, 98, 65, 148, 133, 148, 133,102, 98, 65, 148, 133],
            color: '#fb869f'
        },{
            name: 'Male Marks',
            data: [102, 98, 65, 148, 133,102, 98, 65, 148, 133,102, 98, 65, 148, 133,102, 98, 65, 148, 133,102, 98, 65, 148, 133,102, 98, 65, 148, 133, 148, 133,102, 98, 65, 148, 133],
            color: '#4a99a4'
        }]
    });
});






