<?php


    require_once("../admin/start.php");

    require_once("../site/Forms.php");

    header('Access-Control-Allow-Origin: *');


    $formName = $_REQUEST["formName"];
    $actionVar = $_REQUEST["formName"] . "Action";
    $action = $_REQUEST[$actionVar];

    fb("Ajax formName: " . $formName . " - action: " . $action);

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

        $return["data"] = $data;
    }

    if ($_REQUEST["action"] == "getObjects" && $_REQUEST["idcategory"]) {


        $menu = new Menu(1);
        $filters[] = array(
                            "method" => "value",
                            "idattribute" => 81,
                            "value" => $_REQUEST["idcategory"],
                        );
        $settings["filters"] = $filters;
        $contents = $menu->getContents($settings);

        foreach($contents as $content) {
            $data[] = $content;

        }
        $return["data"] = $data;

    }


    // #################################################################
    // Website
    // #################################################################

    /*

    // ***********************************************
    //  User login
    // ***********************************************
    if ( ($formName == "formLoginAjax" || $formName == "formLogin") && $action == "submit") {
        if ($formName == "formLoginAjax") {
            $form = $forms["loginAjax"];
        } else {
            $form = $forms["login"];
        }
        $form->submited = true;

        if ($form->isValid())
        {
            $user = new User();
            //$user->setUserByEmailAndPassword($form->getFieldValue("inputEmail"), $form->getFieldValue("inputPassword"));
            $user->setUserByUsernameAndPassword($form->getFieldValue("inputUsername"), $form->getFieldValue("inputPassword"));

            if (!$user->id) {
                $form->messages->error[] = $labels->get("login_wrongCredentials");
            } else {
                if ($user->active && ($user->idtipo == 'N' || $user->idtipo == 'P')) {

                    // Log in successful
                    $form->messages->success[] = $labels->get("ajax_successWait");

                    // Operations serverside
                    $user->login();

                    // Operations clientside
                    $return["operation"] = "redirect";
                    $return["param"] = $base->url . "?login=1";
                    //$return["param"] = $user->getUrl();

                    //$return["execute"] = "xpto";
                } else {
                    $form->messages->block[] = $labels->get("login_needToActivate");
                }
            }
        }
    }

    // ***********************************************
    //  Recover password link button
    // ***********************************************
    if ($formName == "formLoginAjax" && $action == "recoverPassword") {
        // Wait
        $page->messages->success[] = $labels->get("ajax_wait");

        // Operations clientside
        $return["operation"] = "redirect";
        $return["param"] = $base->url."user/login.php";

    }

    // ***********************************************
    //  Recover password
    // ***********************************************
    if ($formName == "formRecoverPassword" && $action == "submit") {
        $form = $forms["recoverPassword"];
        $form->submited = true;
        if ($form->isValid())
        {
            try {
                $user = new User();
                $user->email = $form->getFieldValue("inputEmail");
                $user->sendRecoverPasswordEmail();

                $message = $labels->get("recoverPassword_emailSent");
                $message = str_replace("[email]", $user->email, $message);
                $form->messages->success[] = $message;
            }
            catch(Exception $ex)
            {
                $form->messages->error[] = $ex->getMessage();
            }
        }
    }

    // ***********************************************
    //  Resend activation
    // ***********************************************
    if ($formName == "formResendActivation" && $action == "submit") {
        $form = $forms["resendActivation"];
        $form->submited = true;
        if ($form->isValid())
        {
            try {
                $user = new User();
                $user->email = $form->getFieldValue("inputEmail");
                $user->sendActivationEmail();

                $message = $labels->get("resendActivation_emailSent");
                $message = str_replace("[email]", $user->email, $message);
                $form->messages->success[] = $message;
            }
            catch(Exception $ex)
            {
                $form->messages->error[] = $ex->getMessage();
            }
        }
    }

    // ***********************************************
    //  Delete user from administration
    // ***********************************************

    if ( $_REQUEST["action"] == "deleteUser") {
        $user = new User();

        $user->initUserByKey($_REQUEST["parameter"]);
        if ($user->id) {
            $user->delete();
            $return["operation"] = "redirect";
            $return["param"] = "index.php";
        }
    }

    // ***********************************************
    //  Add user from administration
    // ***********************************************

    if ( $formName == "formUsers" && $action == "submit" && $loggedUser->id) {
        $users = new Users();

        $form = $users->getFormEdit($_REQUEST["key"]);
        $form->submited = true;
        if ($form->isValid())
        {
            try {
                $user = new User();
                if ($_REQUEST["key"]) {
                    $user->initUserByKey($_REQUEST["key"]);
                }
                $setPassword = false;

                $user->name = $form->getFieldValue("name");
                $user->email = $form->getFieldValue("email");
                $user->username = $form->getFieldValue("username");
                if ($form->getFieldValue("password")) {
                    $user->password = $form->getFieldValue("password");
                    $setPassword = true;
                }
                $user->address = $form->getFieldValue("address");
                $user->city = $form->getFieldValue("city");
                $user->dateOfBirth = $form->getFieldValue("dateOfBirth");
                $user->job = $form->getFieldValue("job");
                $user->sex = $form->getFieldValue("sex");
                $user->height = $form->getFieldValue("height");
                $user->notes = $form->getFieldValue("notes");
                $user->idgroup = $form->getFieldValue("idgroup");


                if ($loggedUser->idtipo == "N") {
                    // Nutricionista  a adicionar paciente
                    $user->idnutritionist = $loggedUser->id;
                    $user->idtipo = 'P';
                } else {
                    $user->idtipo = $form->getFieldValue("idtipo");
                    $user->idnutritionist = $form->getFieldValue("idnutritionist");
                }
                if ($_REQUEST["key"]) {
                    $user->update($setPassword);
                } else {
                    $user->create();
                }

                $form->messages->success[] = $labels->get("ajax_successWait");
                $return["operation"] = "redirect";
                $return["param"] = "index.php";

            }
            catch(Exception $ex)
            {
                $page->messages->error[] = $ex->getMessage();
            }
        }
    }

    // ***********************************************
    //  Register
    // ***********************************************

    if ( $formName == "formRegister" && $action == "submit") {
        $form = $forms["register"];

        $form->submited = true;

        for($i = 1; $i <= count(Settings::$profiles); $i++) {
            if ($form->getFieldValue("profile" . $i) == $database->true) {

                $profileActivated = true;
            }
        }
        if (!$profileActivated) {
            $page->messages->error[] = $labels->get("register_someProfile");
        } else {
            for($i = 1; $i <= count(Settings::$profiles); $i++) {
                if ($database->boolTF($form->getFieldValue("profile" . $i))) {
                    $validator = new FormValidator();
                    $validator->validateRequired = true;
                    $form->getField("idsport" . $i)->validator = $validator;
                }
            }

            if ($form->isValid())
            {
                try {
                    if ($form->getFieldValue("termsAgree") != $database->true) {
                        $form->messages->error[] = $labels->get("register_termsRequired");
                    } else {
                        $user = new User();
                        $user->name = $form->getFieldValue("inputName");
                        $user->email = $form->getFieldValue("inputEmail");
                        $user->password = $form->getFieldValue("inputPassword");
                        $user->newsletter = $form->getFieldValue("newsletterAgree");
                        $user->minor = $form->getFieldValue("minor");

                        $user->create();

                        for($i = 1; $i <= count(Settings::$profiles); $i++) {
                            $user->isProfile[$i] = $database->boolTF($form->getFieldValue("profile" . $i));
                        }
                        $user->update();

                        // Associate sports
                        for($i = 1; $i <= count(Settings::$profiles); $i++) {
                            if ($form->getFieldValue("profile" . $i) == $database->true) {
                                // Profile chosen
                                if ($form->getFieldValue("idsport" . $i)) {
                                    $user->addModality($form->getFieldValue("idsport" . $i), $i);
                                } else {
                                    $errors = true;
                                    $message = $labels->get("register_fillModality");
                                    $page->messages->error[] = str_replace("[profile]", $labels->get("profile" . ucfirst(Settings::$profiles[$i])), $message);
                                }
                            }
                        }



                        $form->messages->success[] = $labels->get("register_accountCreated");

                        $form->hideFields = true;
                    }
                }
                catch(Exception $ex)
                {
                    $page->messages->error[] = $ex->getMessage();
                }
            }
        }
    }


    // ***********************************************
    //  Change Password
    // ***********************************************

    if ($formName == "formChangePassword" && $action == "submit") {

        $form = $forms["changePassword"];
        $form->submited = true;

        if ($form->isValid())
        {

            try {

                $validatorEmail = new Validator();

                $email = Utils::encrypt(urldecode(base64_decode($_REQUEST["email"])));

                if ($validatorEmail->validEmail($email)) {
                    $user = new User();
                    $user->setUserByEmail($email);

                    if ($user->id) {
                        $user->changePassword($form->getFieldValue("inputPassword"));

                        $form->messages->success[] = $labels->get("changePassword_passwordChanged");
                    }

                }
            }
            catch(Exception $ex)
            {
                $page->messages->error[] = $ex->getMessage();
            }

        }

    }


    */

    // #################################################################
    // Actions from buttons
    // #################################################################


    // Enviar email Atividade Fisica
    /*
    if ($formName == "atividadesFisicas" && $action == "submitEmail" && $loggedUser->email) {
        $email = new Email($loggedUser->email);
        //$email = new Email("franciscocostacampos@gmail.com");
        $message = $labels->get("site_atividadesFisicasEmail");

        $email->message = $message;
        $email->subject = $labels->getTitle("site_atividadesFisicasEmail");

        $list = $loggedUser->listAtividadeFisica();

        $html = "<p style='color:#5f5f5f;font-size:20px;'>";
        foreach($list as $i => $item) {
            if ($i > 0) {
                $html .= "\n<br><br>";
            }
            $atividadeFisica = new AtividadeFisica($item->getValue("atividade"));
            $html .= ($i + 1) . " - ";
            $html .= $atividadeFisica->title;


        }
        $html .= "</p>";

        $message = str_replace("[lista]", $html, $email->message);

        // HTML Email
        $emailHeader = "";
        $emailHeader .= "<center><table border='0' width='600'><tr><td><img src='" . Settings::$domain . "images/pdfLogo.png' width='80'></td>";
        $emailHeader .= "<td align='right' style='color:#5f5f5f;' >";
        $emailHeader .= "<span style='font-weight:bold;font-size:20px;color:#96c11f'>" . $labels->get("site_atividadeFisica") . "</span>";
        $emailHeader .= "<br><span style='font-size:20px;'>" . $loggedUser->name . "</span>";
        $emailHeader .= "<br><span style='font-size:14px;'>" . $base->now . "</span>";
        $emailHeader .= "</td>";
        $emailHeader .= "</tr>";
        $emailHeader .= "<tr><td colspan='2' align='right' style='padding-top:10px;padding-bottom:10px;border-top:1px dotted #5f5f5f;'>";
        $emailHeader .= "<a style='text-decoration:none;color:#a53b8e' href='http://www.slimfito.pt'>www.slimfito.pt</a>";
        $emailHeader .= "</td></tr>";
        $emailHeader .= "<tr><td colspan='2'>";

        $emailFooter = "</td></tr>";
        $emailFooter .= "</table></center>";

        $email->message = $emailHeader . $message . $emailFooter;

        if ($list) {
            $email->send();
            $page->messages->success[] = $labels->get("site_atividadesFisicasEmailEnviado");
        } else {
            $page->messages->error[] = $labels->get("site_atividadesFisicasSemAtividades");
        }
    }

    */



    // #################################################################

    //$return["error"] = $page->messages->error;
    //$return["block"] = $page->messages->block;
    //$return["info"] = $page->messages->info;
    //$return["success"] = $page->messages->success;

    echo json_encode($return);

    $page->end();
