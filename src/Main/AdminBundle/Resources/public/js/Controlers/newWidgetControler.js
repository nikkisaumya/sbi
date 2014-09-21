var app = angular.module('sbi', ['nvd3', 'mgcrea.ngStrap.button', 'mgcrea.ngStrap.select', 'ngAnimate', 'ui.ace']);
app.factory('ApiFactory', function($http) {
    return {
        getApi: function(url, callback){
            $http.get(url)
                .success(function (template) {
                    callback(template);
                });
        }
    }

});

app.factory('DatabaseSourceFactory', function($http) {
    return {
        getSources: function(url, callback){
            $http.get(url + '/databases/list')
                .success(function (template) {
                    callback(template);
                });
        }
    }

});

app.controller('NewWidgetCtrl', function($scope, $filter, $http, ApiFactory, DatabaseSourceFactory) {
    var url = angular.element('#baseUrl')[0].dataset.url;
    DatabaseSourceFactory.getSources(url, function(c){
        $scope.dbs = c;
    });

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

    $scope.getApi = function(){
        ApiFactory.getApi($scope.apiAddress, function(c){
            $scope.widget.code = JSON.stringify(c,null,2);
        });
    };

    $scope.saveWidget = function() {
        console.log($scope.widget);
        $http({
            method: 'POST',
            url: url + '/widgets/save',
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