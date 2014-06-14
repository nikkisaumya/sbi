var app = angular.module('sbi', ['xeditable']);
app.run(function(editableOptions) {
    editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'
});
app.controller('UserProfileCtrl', function($scope, $filter, $http) {
    var url = angular.element('#baseUrl')[0].dataset.url;
    var id = angular.element('#profile')[0].dataset.profile;
    $http({method: 'GET', url: url + '/userProfile/' + id }).
        success(function(callback) {
            $scope.user = {
                id: callback.id,
                name: callback.name,
                email: callback.email,
                last_login: callback.last_login,
                expired: callback.expired,
                locked: callback.locked
            };
        }).
        error(function(callback, status) {
            console.log(status + ' Can not get user profile information');
        });
    $scope.saveUser = function() {
        $http({
            method: 'POST',
            url: url + '/userProfile/' + id + '/edit',
            data: $.param({userProfile: JSON.stringify($scope.user)}),
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