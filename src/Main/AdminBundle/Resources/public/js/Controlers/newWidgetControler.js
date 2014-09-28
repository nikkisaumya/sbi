var app = angular.module('sbi', ['configuration', 'nvd3', 'mgcrea.ngStrap.button', 'mgcrea.ngStrap.select', 'ngAnimate', 'ngGrid']);

app.factory('ApiFactory', apiSource);
apiSource.$inject = ['$http', 'BASE_END_POINT'];

function apiSource($http, BASE_END_POINT) {
    var api =  {
        getApi: getApi,
        getApiSource: getApiSource
    };

    return api;

    function getApi(url) {
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

    function getApiSource() {
        return $http.get(BASE_END_POINT + '/apis/list')
    }
}

app.factory('DatabaseSourceFactory', databaseSource);
databaseSource.$inject = ['$http', 'BASE_END_POINT'];

function databaseSource($http, BASE_END_POINT) {
    var source =  {
        getDatabaseList: getDatabaseList,
        getDatabaseTables: getDatabaseTables,
        getDatabaseTableDefinition: getDatabaseTableDefinition
    };

    return source;

    function getDatabaseList() {
        return $http.get(BASE_END_POINT + '/databases/list');
    }

    function getDatabaseTables() {
        return $http.get(BASE_END_POINT + '/databases/tables/list');
    }

    function getDatabaseTableDefinition(name) {
        return $http.get(BASE_END_POINT+ '/databases/table/' + name);
    }
}

app.controller('NewWidgetCtrl', function($scope, $filter, $http, $sce, ApiFactory, DatabaseSourceFactory, BASE_END_POINT) {
    DatabaseSourceFactory.getDatabaseList()
        .then(
            function(c) {
                c.data.unshift({id:0, address: 'This database'});
                $scope.dbs = c.data;
            },
            function(error) {
                console.log(error);
            }
        );

    DatabaseSourceFactory.getDatabaseTables()
        .then(
            function(c) {
                $scope.tables = c.data;
            },
            function(error) {
                console.log(error);
            }
        );

    ApiFactory.getApiSource()
        .then(
            function(c) {
                $scope.apiSource = c.data;
            },
            function(error) {
                console.log(error);
            }
        );

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
        data: 'myData',
        enableColumnResize: true
    };

    $scope.saveWidget = function() {
        $http({
            method: 'POST',
            url: BASE_END_POINT + '/widgets/save',
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

    $scope.getTable = function(table) {
        console.log(table);
        DatabaseSourceFactory.getDatabaseTableDefinition(table.name)
            .then(
            function(c) {
                if(c.status===200) {
                    $scope.enabledCharts = true;
                    $scope.myData = angular.copy(c.data);
                    $scope.widget.chartType = 0;
                }
            },
            function(error) {
                console.log(error);
            }
        );
    }
});