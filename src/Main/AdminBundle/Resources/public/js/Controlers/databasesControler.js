var app = angular.module('sbi', ['ngTable', 'xeditable']);
app.run(function(editableOptions) {
    editableOptions.theme = 'bs3';
});
app.controller('DatabasesCtrl', function($scope, $filter, $http, ngTableParams) {
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

app.controller('NewDatabaseCtrl', function($scope, $filter, $http, ngTableParams) {
    var url = angular.element('#baseUrl')[0].dataset.url;
    var id = angular.element('#profile')[0].dataset.profile;
//    $http({method: 'GET', url: url + '/databases/' + id }).
//        success(function(callback) {
//            $scope.database = {
//                id: callback.id,
//                name: callback.name,
//                port: callback.port,
//                login: callback.login,
//                address: callback.address,
//                password: callback.password
//            };
//        }).
//        error(function(callback, status) {
//            console.log(status + ' Can not get user profile information');
//        });

    $scope.saveDatabase = function() {
        $http({
            method: 'POST',
            url: url + '/databases/save',
            data: $.param({database: JSON.stringify($scope.database)}),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(callback){
            $.growl('Profile updated successfully', { type: 'success', delay: 2000 });
        }).error(function(err) {
            if(err.field && err.msg) {
                // err like {field: "name", msg: "Server-side error for this username!"}
                $scope.editableForm.$setError(err.field, err.msg);
            } else {
                // unknown error
                $scope.editableForm.$setError('name', 'Unknown error!');
            }
            $.growl('Database error', { type: 'danger' });
        });
    };

});