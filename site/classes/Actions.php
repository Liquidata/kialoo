<?php
	/**
	* Action
	* Every user action is controlled here
	*
	* @copyright  Copyright (c) Liquidata. (http://www.liquidata.pt)
	*/
	class Actions extends ActionsCore
	{
		public static function start()
		{
		    global $page;
		    global $labels;
		    global $loggedUser;

            switch (parent::get("action")) {
                case "createShop":
                    // Create shop
                    $shop = new Shop();
                    
                    $shop->name = $_REQUEST["title"];
                    if ($loggedUser) {
                        $shop->create($loggedUser->id);
                    } else {
                        $shop->create();
                    }
                    
                    $page->messages->success[] = $labels->get("message_shopCreated");
                    $page->redirect($shop->getUrlManage());

                    break;
                case "signout":
                    if ($loggedUser) {
                        $loggedUser->logout();
                        $loggedUser = null;
                    }
                    break;
            }
		}
	}
