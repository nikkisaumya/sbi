var app = angular.module('sbi', ['ngTable', 'mgcrea.ngStrap.modal', 'ngAnimate']);

app.controller('ListCtrl', function($scope, $filter, ngTableParams, $http) {
    $scope.modal = {
        "title": "Title",
        "content": "Body"
    };
    $http({method: 'GET', url: 'users/list'}).
        success(function(callback, status, headers, config) {
            var data = callback;
            $scope.tableParams = new ngTableParams({
                page: 1,            // show first page
                count: 10,           // count per page
                sorting: {name: 'asc'}
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
        error(function(callback, status, headers, config) {
            alert(status + 'Something went wrong');
        });
    });