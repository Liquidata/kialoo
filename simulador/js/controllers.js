var baseUrl = "http://www.kialoolocal.pt/site/angular.php";
var spaceWidth = 40;
var objectWidth = 155;

myApp.service('sharedProperties', function() {
    var orders = [];

    return {
        setOrders: function(value) {
            orders = value;
        },
        getOrders: function() {
            return orders;
        },
        calculateTotal: function() {
            // Calculate Total

            var total = 0;
            angular.forEach(orders, function(order) {
                total = total + parseFloat(order.price);
            });
            return total.toFixed(2);

        }
    }
});


myApp.directive('imageonload', function() {
    return {
        link: function(scope, element, attrs) {
            element.bind('load', function() {
                if (attrs["loadstyle"] != null && attrs["loadstyle"] != "") {
                    $("#" + attrs["id"]).attr("style", attrs["loadstyle"]);
                }

                $("#" + attrs["id"]).draggable({
                     revert: true,
                     start: function(event, ui) {

                        $('#chunksArea').css({'overflow-y': 'visible', 'overflow-x': 'visible'});
                    },
                    stop: function(event, ui) {
                        $('#chunksArea').css({'overflow-y': 'scroll', 'overflow-x': 'hidden'});
                    }
                });

                scope.resetDroppable();

                // Activate Tooltips
                //$('.alerta').tooltip('destroy');$('.alerta').tooltip();

                // Don't let drag places
                $('.place').on('dragstart', function(event) { event.preventDefault(); });

                $('.carousel').carousel();

            });
        }
    };
});

myApp.controller('HomeController', function ($scope, $http, sharedProperties) {
    // Init
    var dragging = false;
    $scope.selectedCategory = 1;
    $scope.selectedCollection = 1;
    $scope.selectedObject = { "start" : true, "image_big" : "images/start.png", "sizes" : [1,2,3] };
    $scope.selectedModel = "";
    $scope.preorder  = [ ];
    $scope.styleObjectsWidth = { };
    $scope.sizeS = false;
    $scope.sizeM = false;
    $scope.sizeL = false;
    $scope.sizeSelected = 0;

    $scope.orders = sharedProperties.getOrders();
    $scope.orderTotal = sharedProperties.calculateTotal();


    // *****************************************
    // Categories
    // *****************************************
    $http.get(baseUrl + '?action=getCategories').success(function(data) {

        $scope.categories = data;
        $scope.selectedCategory = data[0];
        $('.objects').scrollTop(1).scrollTop(0);
        $scope.sizeSelected = 2;
    });

    $scope.setCategory = function (newCategory) {
        $scope.selectedCategory = newCategory;
        $('.objects').scrollTop(1).scrollTop(0);
        if(newCategory.id == "15") { // Pendentes
            $scope.selectedCollection = $scope.collections[3]; // Set Collection Medalhões
            //$scope.selectSize(2); // Set Medium Size
        } else {
            $scope.selectedCollection = $scope.collections[0]; // Set First Collection
        }

    }

    $scope.categoryFilterFn = function (object) {
        var result = object["idcategory"] == $scope.selectedCategory["id"];
        return result;
    }

    $scope.getCategoryClass = function (category) {
        var baseClass = "alerta modern buttonM ";
        return category == $scope.selectedCategory? baseClass + "buttonSelected" : baseClass;
    }

    // *****************************************
    // Collections
    // *****************************************
    $http.get(baseUrl + '?action=getCollections').success(function(data) {

        $scope.collections = data;
        $scope.selectedCollection = data[0];
        $scope.selectSize(1);
    });

    $scope.setCollection = function (newCollection) {

        $scope.selectedCollection = newCollection;
    }

    $scope.collectionFilterFn = function (chunk) {


        var sizeOk = true;

        var sizes;

        sizes = $scope.selectedObject["sizes"];

        if (chunk["size"] != undefined) {
            var chunkSize = parseInt(chunk["size"]);

            if (chunkSize != undefined && sizes != undefined) {
                sizeOk = $.inArray(chunkSize, sizes) > -1;
            }
        }

        var result = chunk["idcollection"] == $scope.selectedCollection["id"] && sizeOk;

        // Match Selected size
        if (chunkSize != undefined && chunkSize != $scope.sizeSelected) {
            result = false;
        }


        return result;
    }

    $scope.getCollectionClass = function (collection) {
        // Init Javascript
        $('.alerta').tooltip('destroy');$('.alerta').tooltip();
        $('.carousel').carousel();

        var baseClass = "modern buttonM ";
        return collection == $scope.selectedCollection ? baseClass + "buttonSelected" : baseClass;
    }

    // *****************************************
    // Objects
    // *****************************************
    $http.get(baseUrl + '?action=getObjects').success(function(data) {
        $scope.objects = data;

        //$scope.selectObject(data[0]);
        $scope.styleObjectsWidth = { width: String(data.length * objectWidth) + 'px' };

    });

    $scope.selectObject = function (object) {
        $scope.selectedObject = object;
        console.log(object);

        // Select Model
        if (object.models.length > 0) {
            $scope.selectedModel = object.models[0];
        } else {
            $scope.selectedModel = "";
        }

        // Add to preorder
        $scope.preorder = [];

        $scope.preorder.push(
            { "index" : "object",
              "object" : object,
              "model" : $scope.selectedModel
            }
        );

        // Set Selected size

        if ($scope.selectedObject != null && $scope.selectedObject.sizes != null && $scope.selectedObject.sizes.length > 0) {
            $scope.sizeSelected = $scope.selectedObject.sizes[0];
        } else {
            $scope.sizeSelected = 0;
        }

    }

    // Set class of selected object
    $scope.getObjectClass = function (id) {
        var objectClass = "alerta ";
        if ($scope.selectedObject != undefined) {

            objectClass += (id == $scope.selectedObject.id ? objectClass + "objectSelected" : objectClass);
        }
        return objectClass;
    }

    // Switch Object
    $scope.switchObject = function (id) {
        for(var i = 0; i < $scope.objects.length; i++) {
            if ($scope.objects[i].id == id) {

                $scope.selectObject($scope.objects[i]);
                var n = $(document).height();
                        $('html, body').animate({ scrollTop: 400 }, 1000);
                break;
            }
        }
    }

    // Reset Droppable
    $scope.resetDroppable = function()
    {
        $(".place").droppable({
            drop: function(event, ui) {
                //console.log($(ui.draggable).attr("id"));
                dragging = true;

                $(this).attr("src",$(ui.draggable).attr("src"));

                // Add chunk to preorder
                var place = $(this).attr("id");

                var newPreorder = $scope.removeChunk(place);

                newPreorder.push(
                    { "index" : $(this).attr("id"),
                      "chunk" : $scope.getChunk($(ui.draggable).attr("id"))
                    }
                );


                $scope.preorder = newPreorder;

            }
        });
    }

    $scope.getObjectsWidth = function()
    {
        if($scope.objects != null) {
            return String($scope.objects.length * 3 * 185) + "px";
        } else {
            return 0;
        }
    }
    // *****************************************
    //   Models
    // *****************************************
    $scope.selectModel = function(model) {
        $scope.selectedModel = model;
        angular.forEach($scope.preorder, function(item) {
           if (item["index"] == "object") {
                item["model"] = model;
            }
         });
    };

    // Set class of selected object
    $scope.getModelClass = function (model) {
        var baseClass = "model modern ";

        return model == $scope.selectedModel ? baseClass + "buttonSelected" : baseClass;
    }

    $scope.showModels = function()
    {
        if ($scope.selectedObject != null && $scope.selectedObject.models != null) {
            if ($scope.selectedObject != "" && $scope.selectedObject.models.length > 0) {
                if ($scope.selectedObject.models.length == 1) {
                    if ($scope.selectedObject.models[0] != "") {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // *****************************************
    //   Chunks
    // *****************************************
    $http.get(baseUrl + '?action=getChunks').success(function(data) {
        $scope.chunks = data;
    });

    $scope.removeChunk = function(place) {
        var newPreorder = [];
        angular.forEach($scope.preorder, function(item) {
           if (item["index"] != place) {
                newPreorder.push(item);
            }
         });
         return newPreorder;
    }

    // Remove Chunk
    $scope.clearPlace = function (id) {
        $("#" + id).attr("src", "images/" + transparentFilename + ".png");

        $scope.preorder = $scope.removeChunk(id);
    }

    // Get Chunk
    $scope.getChunk = function (id)
    {
        for(var i = 0; i < $scope.chunks.length; i++) {
            if ($scope.chunks[i].id == id) {
                return $scope.chunks[i];
                break;
            }
        }
    }

    // Buy chunk
    $scope.buyChunk = function(chunk)
    {
        if (!dragging) {
            var oldPreorder = $scope.preorder;

            var preorder = [];

            preorder.push({
                        "index" : "chunk",
                        "chunk" : chunk
                    });
            $scope.preorder = preorder;
            $scope.addOrder();

            $scope.preorder = oldPreorder;
        }
        dragging = false;
    }
    // Check if should show Size Buttons (S/M/L)
    $scope.showSize = function(dimension)
    {
        var result = false;

        if ($scope.selectedObject != null) {
            if ($scope.selectedObject.start) {
                // Initial State
                return true;
            } else {
                result = $.inArray(dimension, $scope.selectedObject.sizes) > -1;
            }
        }
        return result;
    }

    $scope.getSizeClass = function(dimension)
    {
        var result = dimension == $scope.sizeSelected ? "buttonSelected" : "";
        return result;
    };

    $scope.selectSize = function(dimension)
    {
        $scope.sizeSelected = dimension;
    }

    // *****************************************
    // Orders
    // *****************************************
    $scope.addOrder = function ()
    {
        angular.forEach($scope.preorder, function(item) {
            var newOrder = {};
            newOrder.key = $scope.generateKey();
            if (item.index == "object") {
                newOrder.type = "object";
                newOrder.image = item.object.image_big;
                newOrder.id = item.object.id;
                newOrder.price = item.object.price;
                newOrder.name = item.object.name;
                newOrder.model = item.model;
                newOrder.reference = item.object.reference;
            } else {
                newOrder.type = "chunk";
                newOrder.image = item.chunk.image;
                newOrder.id = item.chunk.id;
                newOrder.price = item.chunk.price;
                newOrder.name = item.chunk.name;
                newOrder.reference = item.chunk.reference;
            }

            sharedProperties.getOrders().push(newOrder);

        });
        $scope.orders = sharedProperties.getOrders();
        $scope.orderTotal = sharedProperties.calculateTotal();
    }



    $scope.showOrderTotal = function()
    {
        return $scope.orderTotal > 0;
    }





    $scope.generateKey = function()
    {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for( var i=0; i < 10; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    // Remove Order Item
    $scope.removeOrderItem = function(key)
    {
        var newOrders = [];
        angular.forEach(sharedProperties.getOrders(), function(order) {
            if (order.key != key) {

                newOrders.push(order);
            }
        });
        sharedProperties.setOrders(newOrders);
        $scope.orders = sharedProperties.getOrders();
        $scope.orderTotal = sharedProperties.calculateTotal();

    }


    // *****************************************
    // Suggestions
    // *****************************************
    $http.get(baseUrl + '?action=getSuggestions').success(function(data) {

        $scope.suggestions = data;
        $("suggestion0").addClass('active');
    });

    $scope.getSuggestionClass = function(suggestion) {
       var baseClass = "item ";

        return suggestion.index == 0 ? baseClass + " active" : baseClass;
    };

    $scope.getSuggestionCursorClass = function(suggestion) {
       var baseClass = "suggestion ";

        return suggestion.index == 0 ? baseClass + " active" : baseClass;
    };





});



myApp.controller('BuyController', function ($scope, $http, $routeParams, sharedProperties) {
    $scope.myForm = {};

    $scope.orderTotal = sharedProperties.calculateTotal();
    $scope.orders = sharedProperties.getOrders();

    // Buy Order via AJAX
    $scope.buyOrder = function()
    {
        $.ajax({
            type:       'POST',
            url:        baseUrl,
            dataType:   'json',
            cache:      false,
            data: { action: 'buyOrder', orders: $scope.orders, info: $scope.myForm },
            success: function(data, status) {
                if (data["error"]) {
                    alert(data.message);
                } else {
                    alert(data.message);
                    //self.location = "/simulator";
                }
            },
            error: function(data, status) {
                alert("Please, try again.");
            }
        });
    }
});


