var app = angular.module('sbi', ['ngTable', 'xeditable']);
app.run(function(editableOptions) {
    editableOptions.theme = 'bs3';
});
app.controller('ListDatabaseCtrl', function($scope, $filter, $http, ngTableParams) {
    var url = angular.element('#baseUrl')[0].dataset.url;
    $http({method: 'GET', url: url + '/databases/list' }).
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

