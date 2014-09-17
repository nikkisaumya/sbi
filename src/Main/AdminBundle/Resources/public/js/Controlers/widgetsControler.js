var app = angular.module('sbi', ['ngTable', 'nvd3', 'mgcrea.ngStrap.button', 'mgcrea.ngStrap.select', 'ngAnimate', 'ui.ace']);
app.controller('WidgetsCtrl', function($scope, $filter, $http, ngTableParams) {
    var url = angular.element('#baseUrl')[0].dataset.url;

    $http({method: 'GET', url: url + '/widgets/list' }).
        success(function(callback) {
            console.table(callback);
            var data = callback;
            $scope.tableParams = new ngTableParams({
                page: 1,            // show first page
                count: 10,           // count per page
                sorting: {id: 'asc'}
            }, {
                total: data.length, // length of data
                getData: function($defer, params) {
                    // use build-in angular filter
                    var orderedData = params.sorting() ?
                        $filter('orderBy')(data, params.orderBy()) :
                        data;

                    $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
                }
            });
        }).
        error(function(callback, status) {
            alert(status + 'Something went wrong');
        });

});
app.controller('NewWidgetCtrl', function($scope, $filter, $http) {
    $scope.options = {
        chart: {
            type: 'historicalBarChart',
            height: 350,
            width: 800,
            margin : {
                top: 20,
                right: 20,
                bottom: 60,
                left: 55
            },
            x: function(d){ return d.label; },
            y: function(d){ return d.value; },
            showValues: true,
            valueFormat: function(d){
                return d3.format(',.4f')(d);
            },
            transitionDuration: 500,
            xAxis: {
                axisLabel: 'X Axis'
            },
            yAxis: {
                axisLabel: 'Y Axis',
                axisLabelDistance: 30
            }
        }
    };
    $scope.data = [{
        key: "Cumulative Return",
        values: [
            { "label" : "A" , "value" : -29.765957771107 },
            { "label" : "C" , "value" : 32.807804682612 },
            { "label" : "D" , "value" : 196.45946739256 },
            { "label" : "13H" , "value" : -5.1387322875705 }
        ]
    }];

    $scope.source = '';
    $scope.sources = [
        {value: 'Source 1'},
        {value: 'Source 2'},
        {value: 'Source 3'},
        {value: 'Source 4'}
    ];
    $scope.type = '';
    $scope.types = [
        {value: 'Linear'},
        {value: 'Gantt'},
        {value: 'Bar'},
        {value: 'Plot'}
    ];
});