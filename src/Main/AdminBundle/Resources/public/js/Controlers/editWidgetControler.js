var app = angular.module('sbi', ['nvd3', 'mgcrea.ngStrap.button', 'mgcrea.ngStrap.select', 'ngAnimate', 'ui.ace']);
app.controller('EditWidgetCtrl', function($scope, $filter, $http) {
    var url = angular.element('#baseUrl')[0].dataset.url;
    var id = angular.element('#profile')[0].dataset.profile;
    $http({method: 'GET', url: url + '/widgets/' + id }).
        success(function(callback) {
            $scope.widget = {
                id: callback.id,
                title: callback.title,
                realTime: callback.realTime,
                code: callback.code,
                source: callback.source,
                type: callback.type
            };
        }).
        error(function(callback, status) {
            console.log(status + ' Can not get user profile information');
        });

    $scope.source = '';
    $scope.sources = [
        {key: 1, value: 'Api'},
        {key: 2,  value: 'Database'}
    ];
    $scope.db = '';
    $scope.dbs = [
        {key: 1, value: '127.0.0.1'},
        {key: 2,  value: 'example.com'},
        {key: 3, value: 'localhost'}
    ];
    $scope.type = '';
    $scope.types = [
        {key: 1, value: 'Linear'},
        {key: 2, value: 'Gantt'},
        {key: 3, value: 'Bar'},
        {key: 4, value: 'Plot'}
    ];

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

    $scope.apiSource = '';
    $scope.getApi = function(){
        $http({method: 'GET', url: $scope.apiAddress }).
            success(function(callback) {
                $scope.apiSource = JSON.stringify(callback,null,2);
            }).
            error(function(callback, status) {
                alert(status + 'Something went wrong');
            });
    };

    $scope.deleteWidget = function(id) {
        $http({
            method: 'DELETE',
            url: url + '/widgets/'+ id + '/remove'
        }).success(function(callback){
            $.growl('Widget deleted successfully', { type: 'success', delay: 2000 });
        }).error(function(err) {
            if(err.field && err.msg) {
                $.growl('Database error', { type: 'danger' });
                console.log(err.msg);
            }
        });
    }

    $scope.saveWidget = function() {
        $http({
            method: 'POST',
            url: url + '/widgets/patch/'+ id,
            data: $.param({database: JSON.stringify($scope.widget)}),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(callback){
            $.growl('Widget updated successfully', { type: 'success', delay: 2000 });
        }).error(function(err) {
            if(err.field && err.msg) {
                $.growl('Database error', { type: 'danger' });
                console.log(err.msg);
            }
        });
    }
});