var app = angular.module('sbi', []);
app.controller('NewDatabaseCtrl', function($scope, $filter, $http) {
    var url = angular.element('#baseUrl')[0].dataset.url;
    var id = angular.element('#profile')[0].dataset.profile;

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
                console.log(err.msg);
            }
        });
    };

});