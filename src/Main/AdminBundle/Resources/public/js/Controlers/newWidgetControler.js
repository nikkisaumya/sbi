var app = angular.module('sbi', ['nvd3', 'mgcrea.ngStrap.button', 'mgcrea.ngStrap.select', 'ngAnimate', 'ngGrid']);

app.factory('ApiFactory', apiSource);
apiSource.$inject = ['$http'];

function apiSource($http){
    var api =  {
        getApi: getApi
    };

    return api;

    function getApi(url){
//        get URL params
        var baseUrl = '';
        var params = {'callback': 'JSON_CALLBACK'};
        var parts = url.split(/[&?]+/);

        for (var i = 0; i < parts.length; i++) {
            var nv = parts[i].split('=');
            if (!nv[0]) continue;
            params[nv[0]] = nv[1] || true;
        }
        _(params).forEach(function(val, key){
            // get base of URL
            if (key.substring(0, 7) == "http://") {
                baseUrl = key;
                delete params[key];
            }
        });

        return $http.jsonp(baseUrl, { params: params });
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
        c.unshift({id:0, address: 'This database'});
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
        var first = _.first(json);
        var arrayKey = [];
        _.keys(first).forEach(function(k, i){
            arrayKey[i] = {
                id: i,
                key: k
            };
        });
        var t = '<table class="table table-bordered"><thead>';
        t+='<th></th>';
        _.forEach(arrayKey, function(k) {
            t += '<th>' + k.key + '</th>';
        });
        t += '</thead>';
        t += '<tbody>';
        var i = 1;
        _.forEach(json, function(v) {
            t += '<tr>';
            t += '<td>' + i + '</td>';
                _.forEach(v, function(td) {
                    t += '<td>';
                    if(_.isObject(td)){
                        t += parseStaticTable(td);
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

    $scope.getApi = function(){
        var promise = ApiFactory.getApi($scope.apiAddress);
        promise.then(
            function(payload) {
                console.log(payload.data);
                jsonObj.init = payload.data;
                $scope.widget.code = {
                    'text': JSON.stringify(jsonObj.code, null, 2),
                    'cleanText': jsonObj.code,
                    'length': jsonObj.length,
                    'table': $sce.trustAsHtml(parseStaticTable(payload.data)),
                    'keys': jsonObj.keys
                };
                $scope.enabledCharts = true;
                $scope.myData = angular.copy(jsonObj.json);
            },
            function(error) {
                console.log(error);
            });

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