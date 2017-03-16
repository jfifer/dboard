var dashboardCtrl = angular.module('dashboardCtrl', []);

dashboardCtrl.controller('homeController', function ($rootScope, $scope, Auth, Portal, Zenoss) {
  $scope.loggedIn = false;
  $scope.username = "";
  $scope.serverGroups = [];
  $scope.servers = [];
  $scope.serverDtl = {};
  $scope.events = [];

  $scope.status_icon = [
    "missing", "okay", "toomany"
  ];
  $scope.severity = [
    "clear", "debug", "info", "warning", "error", "critical"
  ];

  $scope.$on("logIn", function(event, args) {
    $scope.doAuth(args.res);
  });


  $scope.getServers = function(groupId) {
    Portal.query({method: "group", "id": groupId}, function(res) {
      res.forEach(function(server, index) {
        Zenoss.post({action: "SearchRouter", method: "getLiveResults", type: "rpc", tid: 8, query: server.hostname }, function(zen) {
          zen = JSON.parse(zen.res);
          res[index].zenoss = zen.result.results;
        });
      });
      $scope.servers = res;
    });
  };

  $scope.getDetails = function(zUrl) {
    console.log(zUrl);
  };

  $scope.doAuth = function(res) {
    if(parseInt(res.uid) !== -1) {
      $scope.loggedIn = true;
      $scope.username = res.username;
      $scope.getEvents();
      //$scope.getServers();
      $scope.listServerGroups();
    } else {
      $scope.loggedIn = false;
    }
  }

  $scope.getEvents = function() {
     Zenoss.post({action: "EventsRouter", method: "query", type: "rpc", tid: 8, query: null}, [{limit: 50, sort: "lastTime", params:{severity: [3,4,5]}}], function(res) {
        res = JSON.parse(res.res);
        console.log(res.result.events);
        $scope.events = res.result.events;
     });
  }

  $scope.listServerGroups = function() {
    Portal.query(function(res) {
      $scope.serverGroups = res;
    });
  }

  $scope.logout = function() {
    Auth.post(function(res){
      $scope.doAuth(res);
    });
  }

  $scope.init = function() {
    Auth.get(function(res) {
      $scope.doAuth(res);
    });
  }
})
dashboardCtrl.controller('authController', function ($rootScope, $scope, Auth) {
  $scope.login = function(auth) {
    if(angular.element($('.login')).hasClass("ng-valid")) {
      auth.password = sha1(auth.password);
      Auth.post({}, auth, function(res) {
        $rootScope.$broadcast("logIn", { res });
      });
    }
  }
});
