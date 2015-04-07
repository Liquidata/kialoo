<?php
    require_once("../admin/start.php");

    require_once("../site/Forms.php");

    $list = $_REQUEST["list"];
    
    //fb("Ajax formName: " . $formName . " - action: " . $action);

    
    // ***********************************************
    //  List sports
    // ***********************************************

    if ($list == "sportsList") {
        
 
        $list = array();
        $txt =  "SELECT * FROM ( "
                . "\n SELECT id, CONCAT(name3, name2, name1) nameComplete, name1, name2, name3 FROM (SELECT "
                . "\n s1.id "
                . "\n ,(CASE ISNULL(s1.name_" . $base->language . ") WHEN true THEN '' ELSE s1.name_" . $base->language . " END) AS name1  "
                . "\n ,(CASE ISNULL(s2.name_" . $base->language . ") WHEN true THEN '' ELSE s2.name_" . $base->language . " END) AS name2  "
                . "\n ,(CASE ISNULL(s3.name_" . $base->language . ") WHEN true THEN '' ELSE s3.name_" . $base->language . " END) AS name3  "
                . "\n FROM sports s1 "
                . "\n LEFT JOIN sports s2 ON (s2.id = s1.parentid) "
                . "\n LEFT JOIN sports s3 ON (s3.id = s2.parentid) "
                . "\n ) sports2 "
                . "\n ) sports3 "
                . "\n  WHERE ";
                //. "\n       NOT EXISTS (SELECT 1 FROM sports x1 WHERE x1.parentid = sports2.id) ";
        
        if ($_REQUEST["q"] AND $_REQUEST["q"]["term"]) {
            $query = $_REQUEST["q"]["term"];
        } elseif ($_REQUEST["q"]) {
            $query = $_REQUEST["q"];
        }
            
        if ($query) {
            $txt .= "\n       nameComplete LIKE '%" . $query . "%' ";
            $txt .= "\n ORDER BY nameComplete ";
        } elseif ($_REQUEST["id"]) {
            $txt .= "\n      id = " . $_REQUEST["id"] . " ";
        }
        
        
        
        
                
        //echo $txt;
        $sql = $database->sqlGet($txt);
        
        while ($row = $database->sqlRow()) {
            if ($row["name3"]) {
                $nameComplete = $row["name3"] . " - " . $row["name2"] . " - " . $row["name1"];
            } elseif ($row["name2"]) {
                $nameComplete = $row["name2"] . " - " . $row["name1"];
            } else {
                $nameComplete = $row["name1"];
            }
            
            $name = $nameComplete;

            $list[] = array( "id" => $row["id"],
                            "name" => $name
                          );
        }
        
        echo json_encode($list);
        $page->end();
        exit;
    
        
    }

    // #################################################################

    $return["error"] = $page->messages->error;
    $return["block"] = $page->messages->block;
    $return["info"] = $page->messages->info;
    $return["success"] = $page->messages->success;

    echo json_encode($return);

    $page->end();

