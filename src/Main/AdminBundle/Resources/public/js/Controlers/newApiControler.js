var app = angular.module('sbi', ['configuration']);
app.controller('NewApiCtrl', function($scope, $filter, $http, BASE_END_POINT) {
    $scope.saveApiSource = function() {
        var params = {};
        _($scope.params).forEach(function(p){
            params[p.key] = p.value;
        });
        $http({
            method: 'POST',
            url: BASE_END_POINT + '/apis/save',
            data: $.param(
                {
                    api: JSON.stringify($scope.api),
                    params: params
                }
            ),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(callback){
            $.growl('Api source added successfully', { type: 'success', delay: 2000 });
        }).error(function(err) {
            console.log(err);
        });
    };
    $scope.params = [];

    $scope.addParam = function () {
        $scope.params.push({
            key: "",
            value: ""
        });
    };

});