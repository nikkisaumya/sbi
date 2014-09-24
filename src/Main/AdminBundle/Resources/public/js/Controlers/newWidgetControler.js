var app = angular.module('sbi', ['nvd3', 'mgcrea.ngStrap.button', 'mgcrea.ngStrap.select', 'ngAnimate', 'ngTable']);
app.factory('ApiFactory', function($http) {
    return {
        getApi: function(url, callback){
            $http.get(url).success(callback);
        }
    }

});

app.factory('DatabaseSourceFactory', function($http) {
    return {
        getSources: function(url, callback){
            $http.get(url + '/databases/list')
                .success(function (template) {
                    callback(template);
                });
        }
    }

});

app.controller('NewWidgetCtrl', function($scope, $filter, $http, $sce, ApiFactory, DatabaseSourceFactory, ngTableParams) {
    var url = angular.element('#baseUrl')[0].dataset.url;
    DatabaseSourceFactory.getSources(url, function(c){
        $scope.dbs = c;
    });

    $scope.options = {
        chart: {
            type: 'historicalBarChart',
            height: 350,
            width: 800,
            margin : {
                top: 20,
                right: 20,
                bottom: 60,
                left: 55
            },
            x: function(d){ return d.label; },
            y: function(d){ return d.value; },
            showValues: true,
            valueFormat: function(d){
                return d3.format(',.4f')(d);
            },
            transitionDuration: 500,
            xAxis: {
                axisLabel: 'X Axis'
            },
            yAxis: {
                axisLabel: 'Y Axis',
                axisLabelDistance: 30
            }
        }
    };
    $scope.data = [{
        key: "Cumulative Return",
        values: [
            { "label" : "A" , "value" : -29.765957771107 },
            { "label" : "C" , "value" : 32.807804682612 },
            { "label" : "D" , "value" : 196.45946739256 },
            { "label" : "13H" , "value" : -5.1387322875705 }
        ]
    }];

    function parseStaticTable(json){
        // nested loop, better to use recursion, now only 1 level deep
        var t = '<table class="table table-bordered"><thead>';
        t+='<th></th>';
        _.forEach(json.keys, function(k) {
            t += '<th>' + k.key + '</th>'
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
            jsonObj.init = c;
            $scope.widget.code = {
                'text': JSON.stringify(jsonObj.code, null, 2),
                'cleanText': jsonObj.code,
                'length': jsonObj.length,
                'table': $sce.trustAsHtml(parseStaticTable(jsonObj)),
                'keys': jsonObj.keys
            };
//            move below to new function
            var data = jsonObj.code;

            $scope.tableParams = new ngTableParams({
                page: 1,            // show first page
                count: 10,          // count per page
                sorting: {
                    name: 'id'     // initial sorting
                }
            }, {
                total: jsonObj.code.length, // length of data
                getData: function($defer, params) {
                    // use build-in angular filter
                    var orderedData = params.sorting() ?
                        $filter('orderBy')(jsonObj.code, params.orderBy()) :
                        jsonObj.code;

                    $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
                }
            });
        });

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
    }
});