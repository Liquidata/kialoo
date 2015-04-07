<?php
if ($_REQUEST["red"]) {
    $transparentFilename = "_transparent";
} else {
    $transparentFilename = "transparent";
}
?>
<!DOCTYPE html>
<html ng-app="myApp">
<head>
    <meta charset="UTF-8">
    <title>kialoo</title>
    <script src="angular/angular.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.16/angular-route.min.js"></script>
    <link href="../bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="../bootstrap/bootstrap-theme.css" rel="stylesheet" />
     <link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">

    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="../bootstrap/bootstrap.js"></script>

    <script src="js/app.js"></script>
    <script src="js/controllers.js"></script>
    <link href="style.css?<?php echo getRandomString(); ?>" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <script>
        var transparentFilename = "<?php echo $transparentFilename; ?>";
    </script>
</head>
<body>

    <div class="facebook">

        <div class="inner">
            <div class="logo"><img src="http://www.kialoo.pt/logo.jpg" height="150"></div>
            <div id="main" ng-view></div>
            </div>
        </div>

</html>
<?php

function getRandomString($length = 6) {
    $validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ+-*#&@!?";
    $validCharNumber = strlen($validCharacters);

    $result = "";

    for ($i = 0; $i < $length; $i++) {
        $index = mt_rand(0, $validCharNumber - 1);
        $result .= $validCharacters[$index];
    }

    return $result;
}