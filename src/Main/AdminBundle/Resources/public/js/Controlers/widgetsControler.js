var app = angular.module('sbi', []);

app.controller('WidgetsCtrl', function($scope, $filter, $http) {
    var url = angular.element('#baseUrl')[0].dataset.url;

    $http({method: 'GET', url: url + '/userProfile/' + id }).
        success(function(callback) {

        }).
        error(function(callback, status) {

        });

});