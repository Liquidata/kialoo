var myApp = angular.module('myApp', [
    'ngRoute'

]);

myApp.config(['$routeProvider', function($routeProvider) {
    $routeProvider.
    when('/home', {
        templateUrl: 'partials/home.php?transparentFilename=' + transparentFilename,
        controller: 'HomeController'
    }).
    when('/buy', {
        templateUrl: 'partials/buy.html',
        controller: 'BuyController'
    }).
    otherwise({
        redirectTo: '/home'
    });

}]);





