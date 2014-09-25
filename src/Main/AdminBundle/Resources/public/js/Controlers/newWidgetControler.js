var app = angular.module('sbi', ['nvd3', 'mgcrea.ngStrap.button', 'mgcrea.ngStrap.select', 'ngAnimate', 'ngGrid']);

app.factory('ApiFactory', apiSource);
apiSource.$inject = ['$http'];

function apiSource($http){
    var api =  {
        getApi: getApi
    };

    return api;

    function getApi(url, callback){
        $http.get(url)
            .success(callback)
            .error(callback, function(error){
                console.log('Error: ', error);
            });
    }
}

app.factory('DatabaseSourceFactory', databaseSource);
databaseSource.$inject = ['$http'];

function databaseSource($http) {
    var source =  {
        getDatabaseList: getDatabaseList,
        getDatabaseTables: getDatabaseTables
    };

    return source;

    function getDatabaseList(url, callback){
        $http.get(url + '/databases/list')
            .success(callback)
            .error(callback, function(error){
                console.log('Error: ', error);
            });
    }

    function getDatabaseTables(url, callback){
        $http.get(url + '/databases/tables/list')
            .success(callback)
            .error(callback, function(error){
                console.log('Error: ', error);
            });
    }
}

app.controller('NewWidgetCtrl', function($scope, $filter, $http, $sce, ApiFactory, DatabaseSourceFactory) {
    var url = angular.element('#baseUrl')[0].dataset.url;
    DatabaseSourceFactory.getDatabaseList(url, function(c){
        $scope.dbs = c;
    });
    DatabaseSourceFactory.getDatabaseTables(url, function(c){
        $scope.tables = c;
    });

    $scope.options = {
        chart: {
            type: 'pieChart',
            height: 500,
            x: function(d){return d.key;},
            y: function(d){return d.y;},
            showLabels: true,
            transitionDuration: 500,
            labelThreshold: 0.01,
            legend: {
                margin: {
                    top: 5,
                    right: 35,
                    bottom: 5,
                    left: 0
                }
            }
        }
    };
    $scope.data = [
              	 { key: "One", y: 5 },
                 { key: "Two", y: 2 },
                 { key: "Three", y: 9 },
                 { key: "Four", y: 7 },
                 { key: "Five", y: 4 },
                 { key: "Six", y: 3 },
                 { key: "Seven", y: 9 }
            ];

    function parseStaticTable(json){
        // nested loop, better to use recursion, now only 1 level deep
        var t = '<table class="table table-bordered"><thead>';
        t+='<th></th>';
        _.forEach(json.keys, function(k) {
            t += '<th>' + k.key + '</th>';
        });
        t += '</thead>';
        t += '<tbody>';
        var i = 1;
        _.forEach(json.code, function(v) {
            t += '<tr>';
            t += '<td>' + i + '</td>';
                _.forEach(v, function(td) {
                    t += '<td>';
                    if(_.isObject(td)){
                        t += _(td).toString(); //add recursion here
                    }else{
                        t += td;
                    }
                    t += '</td>';
                });
            t += '</tr>';
            i++;
        });
        t += '</tbody>';
        t += '</table>';
        return t;
    }
    var jsonObj = {
        code: [],
        set init (code) {
            this.code = code;
        },
        get length() {
            return this.code.length;
        },
        get json() {
            return this.code;
        },
        get keys() {
            var first = _.first(this.code);
            var arrayKey = [];
            _.keys(first).forEach(function(k, i){
                arrayKey[i] = {
                    id: i,
                    key: k
                };
            });
            return arrayKey;
        }
    };
    $scope.apiAddress = "http://127.0.0.1:8000/app_dev.php/fakeJson";
    $scope.getApi = function(){
        ApiFactory.getApi($scope.apiAddress, function(c){
            $scope.enabledCharts = true;
            jsonObj.init = c;
            $scope.widget.code = {
                'text': JSON.stringify(jsonObj.code, null, 2),
                'cleanText': jsonObj.code,
                'length': jsonObj.length,
                'table': $sce.trustAsHtml(parseStaticTable(jsonObj)),
                'keys': jsonObj.keys
            };
        });

    };

    $scope.getCode = function(){
        $scope.myData = jsonObj.json;

    };
    $scope.gridOptions = {
        data: 'myData'
    };

    $scope.saveWidget = function() {
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
    };
});