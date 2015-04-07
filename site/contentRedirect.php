<?php
error_reporting(0);
    require_once("../admin/start.php");
    
    $currentMenu = $menus->getByUrl();
    
    if ($currentMenu->id) {
        if ($currentMenu->external) {
            $page->redirect($currentMenu->address);
        } else {
            if ($template = $currentMenu->getTemplate()) {
                require_once($template);
            }
        }
    }

    $page->end();
   
