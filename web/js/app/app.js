/**
 * A very simple (messy), implementation of a Todo List in AngularJS.
 * Items are pulled and pushed into the back-end via a RESTful API.
 * 
 * @author Mike Timms <mike@codeeverything.com>
 * 
 */
angular.module('planckApp', ['ngRoute']).config(['$routeProvider', function($routeProvider) {
    $routeProvider.
      when('/', {
        templateUrl: 'partials/list.html',
        controller: 'TodoController'
      }).
      otherwise({
        redirectTo: '/'
      });
}]).controller('TodoController', ['$scope', 'TodoAPI', function ($scope, todo) {
    var defaultItem = function () {
        return {
            id: null,
            name: '',
            completed: false
        };
    }
    
    // store the content of the current item as it is entered
    // by default this will be a new item
    $scope.currentItem = defaultItem();
    
    // populate the todo list upon instantiation
    todo.list().then(function successCallback(response) {
        // this callback will be called asynchronously
        // when the response is available
        $scope.todoList = response.data;
    }, errorHandler);
    
    $scope.viewTodo = function (item) {
        todo.view(item).then(function successCallback(response) {
            $scope.currentItem = item;
        }, errorHandler);
    }
    
    // add a new item to the list of things to do
    $scope.addItem = function () {
        // check for blank entries
        if ($scope.currentItem.name == '') {
            alert('Sorry. You can\'t enter a blank todo!');
            return;
        }
    
        todo.add($scope.currentItem).then(function successCallback(response) {
            // push the item onto the list
            $scope.todoList.push(response.data);
            // reset the newItem text
            $scope.currentItem = defaultItem();
            // focus the new item input
            document.getElementById('todoEntry').focus();
        }, errorHandler);
    };
    
    // remove an item
    $scope.deleteTodo = function (item) {
        todo.delete(item.id).then(function successCallback(response) {
            // remove from list
            var index = $scope.todoList.indexOf(item);
            $scope.todoList.splice(index, 1); 
        }, errorHandler);
    }
    
    $scope.completeTodo = function (item) {
        item.completed = !item.completed;
        todo.edit(item).then(function successCallback(response) {
            var index = $scope.todoList.indexOf(item);
            $scope.todoList[index] = item;
        }, errorHandler);
    }
    
    $scope.editTodo = function (item) {
        // focus the new item input
        document.getElementById('todoEntry').focus();
        
        todo.edit(item).then(function successCallback(response) {
            var index = $scope.todoList.indexOf(item);
            $scope.todoList[index] = item;
            $scope.currentItem = defaultItem();
        }, errorHandler);
    }
    
    /**
     * A generic function to handle errors from any of the actions above
     */
    function errorHandler(response) {
        alert('There was an error completing that action.');
        console.log(response);
    }
      
}]).service('TodoAPI', ['$http', function ($http) {
    // a service to manage the Todo API
    return {
        list: function () {
            return $http({
                method: 'GET',
                url: '/api/todos'
            });
        },
        view: function (item) {
            return $http({
                method: 'GET',
                url: '/api/todos/' + item.id
            })  
        },
        add: function (item) {
            return $http({
                method: 'POST',
                url: '/api/todos',
                data: item
            });
        },
        edit: function (item) {
            return $http({
                method: 'PUT',
                url: '/api/todos/' + item.id,
                data: item
            });
        },
        delete: function (id) {
            return $http({
                method: 'DELETE',
                url: '/api/todos/' + id
            });
        }
    }
}]);