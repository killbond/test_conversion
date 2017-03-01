'use strict';

Array.prototype.last = function(value) {
    if(typeof value != 'undefined') {
        this[this.length - 1] = value;
        return this;
    }
    return this[this.length - 1];
};

var app = angular.module('conversionReport', ['ui.router', 'ya.nouislider']);

app.config(function($stateProvider, $urlRouterProvider, $locationProvider) {
    $stateProvider.state('app', {
        url: '/',
        views: {
            'app': {
                controller: 'appController',
                templateUrl: 'js/views/app.html',
                resolve: {
                    data: function(DataService) {
                        return DataService.get();
                    }
                }
            },
            'slider@app': {
                controller: 'sliderController',
                templateUrl: 'js/views/slider.html',
                resolve: {
                    range: function($http) {
                        return $http.get('/slider/range').then(function(response) {
                            return response.data;
                        });
                    }
                }
            }
        }
    });

    $locationProvider.html5Mode(true);
    $urlRouterProvider.otherwise('/');
});

app.directive('hcChart', function () {
    return {
        restrict: 'E',
        template: '<div></div>',
        scope: {
            options: '=',
            data: '='
        },
        link: function (scope, element, attrs) {
            var chart = new Highcharts.Chart(element[0], scope.options);
            scope.$watch('data', function(data) {
                chart.series[0].setData(data, true);
            }, true);
        }
    };
});

app.service('DataService', function($http) {
    return {
        get: function(ranges) {
            ranges = ranges || false;

            var params = {};
            if(ranges) {
                params['ranges[]'] = ranges;
            }

            return $http.get('/data', { params: params }).then(function(response) {
                return response.data;
            });
        },
        weeks: function() {
            return $http.get('/weeks').then(function(response) {
                return response.data;
            });
        },
        months: function() {
            return $http.get('/months').then(function(response) {
                return response.data;
            });
        },
        prepare: function(data) {
            data = data || data;
            if(!data) return [];

            var prepared = [];
            angular.forEach(data, function(item) {
                var start = moment(item.start * 1000).format('DD.MM.YYYY'),
                    end = moment(item.end * 1000).format('DD.MM.YYYY');
                prepared.push([start + ' - ' + end, item.value * 100]);
            });
            return prepared;
        }
    };
});

app.controller('appController', function($scope, data, DataService) {

    var update = function(data) {
        $scope.data = DataService.prepare(data);
        $scope.loading = false;
    };

    update(data);
    $scope.chartOptions = {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Conversion report'
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Conversion, % (percents)'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Conversion: <b>{point.y:.1f} %</b>'
        },
        series: [{
            name: 'Conversion',
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y:.1f}', // one decimal
                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    };

    $scope.loading = false;
    $scope.$on('chart:update', function(event, ranges) {
        $scope.loading = true;
        if(typeof ranges == 'string') {
            switch(ranges) {
                case 'months': {
                    DataService.months().then(update);
                    break;
                }
                case 'weeks': {
                    DataService.weeks().then(update);
                    break;
                }
            }
        } else {
            DataService.get(ranges).then(update)
        }
    });

});

app.controller('sliderController', function($scope, $rootScope, range) {

    var secondsInDay = 24 * 60 * 60,
        formatter = function(timestamp) {
            return moment(timestamp * 1000).format('DD.MM.YYYY');
        };

    $scope.options = {
        start: [range.start, range.end],
        connect: [false, true, false],
        step: secondsInDay,
        margin: secondsInDay,
        tooltips: true,
        range: {
            min: range.start,
            max: range.end
        },
        pips: {
            mode: 'positions',
            values: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
            density: 1,
            format: {
                to: formatter,
                from: function(value) { return value; }
            }
        },
        format: {
            to: formatter,
            from: function(value) { return value; }
        }
    };

    $scope.slide = false;
    $scope.eventHandlers = {
        change: function(values, handle, unencoded) {
            $scope.options.start = unencoded;
            $scope.ranges = unencoded;
        },
        start: function() {
            $scope.slide = true;
        },
        end: function() {
            $scope.slide = false;
        }
    };

    $scope.add = function() {
        $scope.options.start
            .push(range.end);

        $scope.options.connect
            .last(true)
            .push(false);

        $scope.ranges = $scope.options.start;
    };

    $scope.delete = function() {
        $scope.options.start
            .pop();

        $scope.options.connect
            .pop();

        $scope.options.connect
            .last(false);

        $scope.ranges = $scope.options.start;
    };

    $scope.weeks = function() {
        var start = [],
            connect = [false],
            pointer = moment(range.start * 1000).isoWeekday(1);

        do {
            start.push(pointer.unix());
            connect.push(true);
            pointer.add(1, 'weeks');
        } while(pointer.unix() <= range.end);

        connect.last(false);
        $scope.options.start = start;
        $scope.options.connect = connect;
        $scope.ranges = 'weeks';
    };

    $scope.months = function() {
        var start = [],
            connect = [false],
            pointer = moment(range.start * 1000).endOf('month');

        do {
            start.push(pointer.unix());
            connect.push(true);
            pointer.add(1, 'months');
        } while(pointer.unix() <= range.end);

        connect.last(false);
        $scope.options.start = start;
        $scope.options.connect = connect;
        $scope.ranges = 'months';
    };

    $scope.reset = function() {
        $scope.options.start = [range.start, range.end];
        $scope.options.connect = [false, true, false];
        $scope.ranges = [range.start, range.end];
    };

    $scope.ranges = [range.start, range.end];
    $scope.update = function() {
        $rootScope.$broadcast('chart:update', $scope.ranges);
    };

});