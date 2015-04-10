<a name="start"></a>
<div class="coluna1">

        <!-- List Categories -->
        <div class="categories bubble">
            <div class="bubbleTitle bubbleLeft" style="float:left;">Produtos</div>
            <div style="float:left;">
                <div class="category" ng-repeat="category in categories">
                    <a ng-click="setCategory(category)" ng-class="getCategoryClass(category)">
                        <div  class="title">{{category.name}}</div>
                    </a>
                </div>
            </div>
        </div>

        <div style="clear:both;"></div>

        <!-- List Objects -->
        <div class="objects bubble showScrollbar">
           <div class="objectsInner" ng-style="styleObjectsWidth">
                <div class="object" ng-repeat="item in objects | filter:categoryFilterFn" >
                    <a ng-click="switchObject(item.id)" ><img rel="tooltip" data-html="true" data-placement="bottom" ng-class="getObjectClass(item.id)"  ng-src="{{item.image_small}}" title="{{item.name}} | {{item.price}} &euro; <br> Clique para selecionar"/></a>
                </div>
            </div>
        </div>

        <!-- List Models -->
        <div class="bubble models" ng-show="showModels()">
            <div ng-click="selectModel(model)" ng-repeat="model in selectedObject.models" ng-class="getModelClass(model)">
                {{model}}
            </div>
        </div>
        <div style="clear:both;"></div>

        <!-- Show Object -->
        <div  class="bubble showObject" style="background-repeat: no-repeat;position:relative;background-image: url({{selectedObject.image_big}});" >
            <img class="place" imageonload ng-repeat="place in selectedObject.places" src="images/<?php echo $_REQUEST["transparentFilename"]; ?>.png" loadstyle="position:absolute;top:{{place.y}};left:{{place.x}};" id="{{place.id}}" ng-click="clearPlace(place.id)"  index="{{place.index}}" />
        </div>

        <div class="objectActions">
            <!-- Reset everything -->

            <div>
             <a href="index.php">
                <button type="button" class="alerta buttonS modernSmall btnActionSmall " style="width:100%">Recomeçar</button>
            </a>
            </div>

            <!-- Make Order-->
            <div class="buttonAddOrder">
                <button type="button" class="buttonM modern btnAction" ng-click="addOrder()">Adicionar</button>
            </div>

        </div>
        <div style="clear:both;"></div>

        <!-- List Collections -->
        <div class="collections bubble">
            <div class="bubbleTitle bubbleLeft" style="float:left;">{{selectedCategory.placeholder_pt}}</div>
            <div style="float:left;">
                <div class="collection" ng-repeat="item in collections | filter: { placeholder_pt : selectedCategory.placeholder_pt }">
                    <a ng-click="setCollection(item)" ng-class="getCollectionClass(item)">
                        <div  class="title">{{item.name}}</div>
                    </a>
                </div>
            </div>
        </div>
        <div style="clear:both;"></div>

        <!-- Show Sizes -->
        <div class="sizes">

            <button type="button" class="buttonS modernSmall buttonSize" ng-click="selectSize(2)" ng-show="showSize(2)" ng-class="getSizeClass(2)">M;</button>
            <button type="button" class="buttonS modernSmall buttonSize" ng-click="selectSize(3)" ng-show="showSize(3)" ng-class="getSizeClass(3)">L</button>

        </div>

        <!-- Show Chunks -->
        <div id="chunksArea" class="bubble chunks showScrollbar">
            <div class="infoChunks" style="">
                <img rel="tooltip" data-placement="bottom" class="alerta chunk" ng-dblclick="buyChunk(item);" ng-repeat="item in chunks | filter:collectionFilterFn" id="{{item.id}}" ng-src="{{item.image}}" data-html="true"  title="{{item.name}} | {{item.price}} &euro; <br> Arraste para configurar <br> Duplo-clique para encomendar" imageonload />
            </div>
        </div>

</div>


<div class="coluna2">

    <!-- Suggestions -->
    <div id="carousel-example-generic" class="bubble suggestions carousel slide" data-ride="carousel">
        <!-- Titulo Ajuda-->
        <div class="buttonAddOrder">
            <button type="button" class="alerta buttonS modernSmall btnActionSmall" rel="tooltip" data-html="true" data-placement="bottom" style="margin:0 auto;display:table;" title="<img style='display:table;' src='images/ajuda.jpg'>">Ajuda</button>
        </div>
        <div class="info" style="padding-bottom:10px;">
            <!-- Indicators -->
            <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" ng-class="getSuggestionCursorClass(suggestion)" ng-repeat="suggestion in suggestions" data-slide-to="{{suggestion.index}}" />
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div ng-repeat="suggestion in suggestions" ng-class="getSuggestionClass(suggestion)">
                    <img ng-src="{{suggestion.image}}" width="150" height="150" />
                </div>

            </div>

            <!-- Controls -->
            <a class="left carousel-control" href="javascript: void 0;" onclick="$('.carousel').carousel('prev');" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="javascript: void 0;" onclick="$('.carousel').carousel('next');" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            </a>


        </div>
    </div>

    <script>
        $('.carousel').carousel({
          interval: 1000
        });
    </script>

    <!-- Make Order -->
    <div class="bubble orders">
        <div style="margin:10px auto;display:table;">
            <div class="bubbleTitle bubbleTop">A sua encomenda</div>
        </div>
        <div class="info" style="padding-bottom:10px;">
            <div class="order" ng-repeat="order in orders">

                <div class="remove"><a ng-click="removeOrderItem(order.key)" title="Remover"><img src="images/remove.png" /></a></div>
                <div class="item">
                    <div class="title">{{order.name}} {{order.model}}</div>
                    <div class="price">{{order.price}} &euro;</div>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div class="total" >
                <div class="title">Total</div>
                <div class="price">{{orderTotal}} &euro;</div>
            </div>

            <!-- Make Order -->
            <div class="makeOrder">
                <a href="#buy">
                    <button ng-show="showOrderTotal()" type="button" class="buttonM modern btnAction"><span class="glyphicon glyphicon-shopping-cart"></span> Checkout</button>
                </a>

            </div>
        </div>
    </div>





</div>

