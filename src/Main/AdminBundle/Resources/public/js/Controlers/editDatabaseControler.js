var app = angular.module('sbi', []);
app.controller('EditDatabaseCtrl', function($scope, $filter, $http) {
    var url = angular.element('#baseUrl')[0].dataset.url;
    var id = angular.element('#profile')[0].dataset.profile;
    $http({method: 'GET', url: url + '/databases/' + id }).
        success(function(callback) {
            $scope.database = {
                id: callback.id,
                port: callback.port,
                login: callback.login,
                address: callback.address
            };
        }).
        error(function(callback, status) {
            console.log(callback, ' Can not get user profile information');
        });

    $scope.saveDatabase = function() {
        $http({
            method: 'POST',
            url: url + '/databases/patch/'+ id,
            data: $.param({database: JSON.stringify($scope.database)}),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(callback){
            $.growl('Database updated successfully', { type: 'success', delay: 2000 });
        }).error(function(err) {
            if(err.field && err.msg) {
                $.growl('Database error', { type: 'danger' });
                console.log(err.msg);
            }
        });
    };

    $scope.deleteDatabase = function(id) {
        console.log(id);
        $http({
            method: 'DELETE',
            url: url + '/databases/'+ id + '/remove'
        }).success(function(callback){
            $.growl('Database deleted successfully', { type: 'success', delay: 2000 });
        }).error(function(err) {
            if(err.field && err.msg) {
                $.growl('Database error', { type: 'danger' });
                console.log(err.msg);
            }
        });
    }

});