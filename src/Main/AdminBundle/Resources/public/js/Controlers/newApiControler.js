var app = angular.module('sbi', ['configuration']);
app.controller('NewApiCtrl', function($scope, $filter, $http, BASE_END_POINT) {
    function prepareParams() {
        var params = {};
        _($scope.params).forEach(function(p){
            params[p.key] = p.value;
        });
        return params;
    }
    $scope.saveApiSource = function() {
        $http({
            method: 'POST',
            url: BASE_END_POINT + '/apis/save',
            data: $.param(
                {
                    api: JSON.stringify($scope.api),
                    params: prepareParams()
                }
            ),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(){
            $.growl('Api source added successfully', { type: 'success', delay: 2000 });
        }).error(function(err) {
            console.log(err);
        });
    };
    $scope.params = [];
    $scope.params.push({
        key: "callback",
        value: "JSON_CALLBACK",
        disabled: true
    });
    $scope.addParam = function () {
        $scope.params.push({
            key: "",
            value: "",
            disabled: false
        });
    };

    $scope.testApi = function() {
        console.log($scope.api.url, prepareParams());
        $http.jsonp($scope.api.url, {params: prepareParams()})
            .then(function(succes){
                console.log(succes);
                if(succes.status==200 && _(succes.data).isObject ) {
                    $scope.canSaveApi = true;
                }
            }, function(error){
                if(error.status==404) {
                    $scope.error = true;
                }
               console.log(error);
            });
    };

    $scope.removeParam = function(index) {
         $scope.params.splice(index, 1);
    }

});