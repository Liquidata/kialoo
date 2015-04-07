<?php
    require_once("../admin/start.php");

    $aResponse['error'] = false;
    $aResponse['message'] = '';
    
    if(isset($_POST['action']))
    {
    	if(htmlentities($_POST['action'], ENT_QUOTES, 'UTF-8') == 'rating')
    	{
    		$id = intval($_POST['idBox']);
    		$rate = floatval($_POST['rate']);
    		$place = $_POST['place'];
    		
    		//if ($loggedUser) {
    		    
    		    //Stars::save($place, $id, $rate, $loggedUser->id);
    		    Stars::save($place, $id, $rate, null);
    		    
    		    $aResponse['message'] = $labels->get("global_starSaved");
    			echo json_encode($aResponse);
    		/*    
    		} else {
    		    $aResponse['error'] = true;
    			$aResponse['message'] = $labels->get("profile_getLogin");
    			echo json_encode($aResponse);
    		}
    		*/
    	}
    	else
    	{
    		$aResponse['error'] = true;
    		$aResponse['message'] = '"action" post data not equal to \'rating\'';
    		echo json_encode($aResponse);
    	}
    }
    else
    {
    	$aResponse['error'] = true;
    	$aResponse['message'] = '$_POST[\'action\'] not found';
    	echo json_encode($aResponse);
    }

$page->end();
