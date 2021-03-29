/*
 *  Document   : progression_report_charts.js
 *  Author     : Anna
 *  Description: Custom JS code used in Charts Page
 */

var ProgressionRptChart = function() {
 
    // Flot charts, for more examples you can check out http://www.flotcharts.org/flot/examples/
    var initChartsFlot = function($levels, $ecc_element, $attempts, $level_1_data, $level_2_data, $level_3_data, $elements_short_label){
        // Get the elements where we will attach the charts
        var $flotLines      = jQuery('.js-flot-lines');

        var $data = [];
        var $colors = [];
        $levels.forEach(function(item) {
            switch(item) {
                case 1:
                    $data.push({
                        label: 'Level-1',
                        data: $level_1_data,
                        lines: {
                            show: true,
                            fill: false,
                            fillColor: {
                                colors: [{opacity: .7}, {opacity: .7}]
                            }
                        },
                        points: {
                            show: true,
                            radius: 6
                        }});
                    $colors.push('#333333');
                    break;
                case 2:
                    $data.push({
                        label: 'Level-2',
                        data: $level_2_data,
                        lines: {
                            show: true,
                            fill: false,
                            fillColor: {
                                colors: [{opacity: .7}, {opacity: .7}]
                            }
                        },
                        points: {
                            show: true,
                            radius: 6
                        }});
                    $colors.push('#5c90d2');
                    break;
                case 3:
                    $data.push({
                        label: 'Level-3',
                        data: $level_3_data,
                        lines: {
                            show: true,
                            fill: false,
                            fillColor: {
                                colors: [{opacity: .7}, {opacity: .7}]
                            }
                        },
                        points: {
                            show: true,
                            radius: 6
                        }});
                    $colors.push('#abe37d');
                    break;
            }
        });

        function percentFormatter(v, axis) {
			return v.toFixed(axis.tickDecimals) + " %";
		}

        // Init lines chart
        jQuery.plot($flotLines, $data,
            {
                colors: $colors,
                legend: {
                    show: true,
                    position: 'nw',
                    backgroundOpacity: 0
                },
                grid: {
                    borderWidth: 0,
                    hoverable: true,
                    clickable: true
                },
                yaxis: {
                    axisLabel: $elements_short_label + ' Element: ' + '<strong>' + $ecc_element + '</strong>',
                    axisLabelPadding : 20,
                    tickColor: '#f5f5f5',
                    ticks: 3,
                    tickFormatter: percentFormatter
                },
                xaxis: {
                    axisLabel: 'Number of Attempts',
                    axisLabelPadding : 20,
                    ticks: $attempts,
                    tickColor: '#f5f5f5'
                }
            }
        );

        // Creating and attaching a tooltip to the classic chart
        var previousPoint = null, ttlabel = null;
        $flotLines.bind('plothover', function(event, pos, item) {
            if (item) {
                if (previousPoint !== item.dataIndex) {
                    previousPoint = item.dataIndex;

                    jQuery('.js-flot-tooltip').remove();
                    var x = item.datapoint[0], y = item.datapoint[1];

                    // if (item.seriesIndex === 0) {
                    //     ttlabel = '$ <strong>' + y + '</strong>';
                    // } else if (item.seriesIndex === 1) {
                    //     ttlabel = '<strong>' + y + '</strong> sales';
                    // } else {
                    //     ttlabel = '<strong>' + y + '</strong> tickets';
                    // }

                    ttlabel = '<strong>' + y + '</strong> %';

                    jQuery('<div class="js-flot-tooltip flot-tooltip">' + ttlabel + '</div>')
                        .css({top: item.pageY - 45, left: item.pageX + 5})
                        .appendTo("body")
                        .show();
                }
            }
            else {
                jQuery('.js-flot-tooltip').remove();
                previousPoint = null;
            }
        });
    };

    return {
        init: function ($levels, $ecc_element, $attempts, $level_1_data, $level_2_data, $level_3_data, $elements_short_label) {
            // Init Flot charts
            initChartsFlot($levels, $ecc_element, $attempts, $level_1_data, $level_2_data, $level_3_data, $elements_short_label);
        }
    };
}();

