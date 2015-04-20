<?php


    require_once("../admin/start.php");

    require_once("../site/Forms.php");

    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Headers: accept, content-type");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }


    // #################################################################
    // Simulator
    // #################################################################

    if ($_REQUEST["action"] == "getCategories") {
        $database->sqlGet("SELECT * FROM categories");
        while($row = $database->sqlRow()) {
            $row["id"] = $row["id"];
            $row["name"] = $row["name_" . $base->language];
            $data[] = $row;


        }
    }

    if ($_REQUEST["action"] == "getCollections") {
        $database->sqlGet("SELECT * FROM collections");
        while($row = $database->sqlRow()) {
            $row["id"] = $row["id"];
            $row["name"] = $row["name_" . $base->language];
            $data[] = $row;


        }
    }

    if ($_REQUEST["action"] == "getSizes") {
        $database->sqlGet("SELECT * FROM sizes");
        while($row = $database->sqlRow()) {
            $row["id"] = $row["id"];
            $row["botoes"] = $row["name_" . $base->language];
            $row["medalhas"] = $row["medalhas_" . $base->language];
            $data[] = $row;


        }
    }

    if ($_REQUEST["action"] == "getObjects") {
        $menu = new Menu(1);
        $contents = $menu->getContents();

        foreach($contents as $j => $content) {
            $info = array();
            $info["id"] = $content->id;
            $info["idcategory"] = $content->getValue("idcategory");
            $info["name"] = $content->getValue("name");
            $info["image_small"] = $content->getValue("image_small")->getUrl("full");
            $info["image_big"] = $content->getValue("image_big")->getUrl("full");
            $info["price"] = $content->getValue("price");
            $info["reference"] = $content->getValue("reference");

            // Get Sizes
            $info["sizes"] = array();
            for($i = 1; $i < 4; $i++) {
                if ($content->getValue("chunk" . $i) == $database->true) {
                    $info["sizes"][] = $i;
                }
            }
            // Get Places
            $info["places"] = array();

            $places = explode("|", $content->getValue("places"));
            foreach($places as $i => $place) {
                $place2 = split(",", $place);

                $newPlace["id"] = "place_" . $j . "_" . $i;
                $newPlace["x"] = $place2[0] . "px";
                $newPlace["y"] = $place2[1] . "px";
                $newPlace["index"] = $i+1;
                $info["places"][] = $newPlace;
            }

            // Get Models
            $info["models"] = array();

            $info["models"] = explode("|", $content->getValue("models"));

            $data[] = $info;
        }

    }

    if ($_REQUEST["action"] == "getChunks") {

        $menu = new Menu(2);
        $contents = $menu->getContents();

        foreach($contents as $j => $content) {
            $info = array();
            $info["id"] = $content->id;
            $info["idcollection"] = $content->getValue("idcollection");
            $info["name"] = $content->getValue("name");
            $info["image"] = $content->getValue("image")->getUrl("full");
            $info["price"] = $content->getValue("price");
            $info["size"] = $content->getValue("size");
            $info["reference"] = $content->getValue("reference");

            $data[] = $info;
        }
    }

    if ($_REQUEST["action"] == "getSuggestions") {
        $menu = new Menu(3);
        $contents = $menu->getContents();

        foreach($contents as $j => $content) {
            $info = array();
            $info["id"] = $content->id;
            $info["index"] = $j;
            $info["image"] = $content->getValue("image")->getUrl("full");
            $data[] = $info;
        }
    }

    if ($_REQUEST["action"] == "buyOrder") {
        $info = $_REQUEST["info"];
        $orders = $_REQUEST["orders"];
        $message = array();

        $error = false;

        if (!$orders) {
            $error = true;
            $message[] = $labels->get("order_errorNoOrder");
        } else {
            if (!$info["name"]) {
                $error = true;
                $message[] = $labels->get("order_nameRequired");
            }
            if (!$info["morada"]) {
                $error = true;
                $message[] = $labels->get("order_moradaRequired");
            }
            if (!$info["cidade"]) {
                $error = true;
                $message[] = $labels->get("order_cidadeRequired");
            }
            if (!$info["codigoPostal1"] || !$info["codigoPostal2"]) {
                $error = true;
                $message[] = $labels->get("order_codigoPostalRequired");
            }
            if (!$info["email"]) {
                $error = true;
                $message[] = $labels->get("order_emailRequired");
            } else {
                if (!Validator::validEmail($info["email"])) {
                    $error = true;
                    $message[] = $labels->get("order_emailNotValid");
                }
            }
        }

        $finalMessage = "";
        foreach($message as $line) {
            $finalMessage .= $line . "\n";
        }

        if ($error) {
            $finalMessage = $labels->get("order_viewErrors") . "\n\n" . $finalMessage;
        } else {
            $finalMessage = $labels->get("order_finalMessage");

            // Enviar Email

            $email = new Email(Settings::$emailAdmin);
            //$email = new Email("franciscocostacampos@gmail.com");
            $email->subject = $labels->getTitle("order_email");

            $emailMessage = "<h3>Comprador</h3>";



            $emailMessage .= "<p>Nome: " . $info["name"] . "</p>";
            $emailMessage .= "<p>Email: " . $info["email"] . "</p>";
            $emailMessage .= "<p>Morada: " . $info["morada"] . "</p>";
            $emailMessage .= "<p>Cidade: " . $info["cidade"] . "</p>";
            $emailMessage .= "<p>Código Postal: " . $info["codigoPostal1"] . " " . $info["codigoPostal2"] . "</p>";
            $emailMessage .= "<p>Informações adicionais: " . $info["informacoes"] . "</p>";

            $emailMessage .= "<br><br><h3>Encomenda</h3>";

            foreach($orders as $item) {
                $emailMessage .= "<p>";
                $emailMessage .= "<img src='" . $item["image"] . "' />";
                //$emailMessage .= "<br>Tipo: " . $item["type"];
                $emailMessage .= "<br>Referência: " . $item["reference"];

                $emailMessage .= "<br>Nome: " . $item["name"];
                $emailMessage .= "<br>Preço: " . $item["price"] . "&euro;";
                if ($item["type"] == "object") {
                    $emailMessage .= "<br>Tamanho: " . $item["model"];
                }
                $emailMessage .= "</p>";
                $emailMessage .= "<hr>";
            }


            $email->message = $emailMessage;

            $email->send();
        }

        $data["message"] = $finalMessage;
    }


    // #################################################################

    echo json_encode($data);

    $page->end();
