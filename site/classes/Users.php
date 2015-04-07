<?php
    /**
	* Users
	* Functions to manage users
	*
	* @copyright  Copyright (c) Liquidata. (http://www.liquidata.pt)
	*/
    class Users
    {
        /**
         * Gets the html to show filter above admin table
         */
        public function getFiltersForm()
        {
            global $labelsAdmin, $base;
            
            $haveFilters = false;
            
            $form = new Form("filterContents");
            $form->class = "form-horizontal";
            $form->legend = $labelsAdmin->get("contents_filters");


            $field = new FormFieldSelect("idusertype");
            $field->label = $labelsAdmin->get("users_userType");
            $field->importTable = "cm_usersTypes";
            $field->importTableValue = "id" ;
            $field->importTableDisplay = "name_" . $base->language;
            $field->value = $_REQUEST["idusertype"];
            $form->addField($field);
            
            $button = new FormButton();
            $button->name = "submit";
            $button->label = $labelsAdmin->get("global_filter");
            $button->type = "submit";
            $button->icon = "tick";
            $button->class = "btn btn-primary";
            $form->addButton($button);
            
            return $form;
        }
        
        /**
         * Gets the html to show filter above admin table
         */
        private function getFilters()
        {
            $html = null;
            $filters = $this->getFiltersForm();
            if ($filters) {
                
                $html = $filters->getAllForm();
            }
            
            return $html;
        }
        
        /**
         * Show User List
         */
        public function showList()
        {
            global $loggedUser, $labels, $labelsAdmin;
            
            
            $html = "";
            
            if ($loggedUser->id) {
                
                if ($_REQUEST["a"] == "add" || $_REQUEST["a"] == "edit") {
                    $form = $this->getFormEdit($_REQUEST["key"]);
                    $html .= $form->getAllForm();
                    
                    
                    $html .= "\n" . '<script>';
                    $html .= "\n" . '   $( document ).ready(function() { ';
                    $html .= "\n" . '       function updateNutricionista() {';
                    $html .= "\n" . '           $("#controlGroupformUsers_idnutritionist").hide(200); ';
                    $html .= "\n" . '           $("#controlGroupformUsers_idgroup").hide(200);';
                    $html .= "\n" . '           if ($("#formUsers_idtipo").val() == "P") {';
                    $html .= "\n" . '               $("#controlGroupformUsers_idnutritionist").show(200);';
                    $html .= "\n" . '               $("#controlGroupformUsers_idgroup").show(200);';
                    $html .= "\n" . '               ';
                    $html .= "\n" . '           }';
                    $html .= "\n" . '       }';
                    $html .= "\n" . '       $("#formUsers_idtipo").on("change", function() { ';
                    $html .= "\n" . '           updateNutricionista(); ';
                    $html .= "\n" . '       }); ';
                                            
                    $html .= "\n" . '       updateNutricionista();';
                    $html .= "\n" . '   }); ';
                    $html .= "\n" . '</script>';
            
                    
                } else {
                    if ($loggedUser->idtipo == "A" || $loggedUser->admin) {
                        $form = $this->getFilters();
                
                        $html .= $form;
                    }
            
                    $database = new Database();
                    
                    
                    
                    if ($loggedUser->admin) {
                        $txt = "SELECT * FROM cm_users ";
                        if ($_REQUEST["idusertype"]) {
                            $txt .= " WHERE idtipo = '" . $_REQUEST["idusertype"] . "' ";
                        }
                        $txt .= "ORDER BY name";
                    } else {
                        if ($loggedUser->idtipo = "N") {
                            $txt = "SELECT * FROM cm_users WHERE idnutritionist = " . $loggedUser->id . " ORDER BY name";
                        } 
                    }
                    
                    $database->sqlGet($txt);
                    $users = array();
                    while ($row = $database->sqlRow()) {
                        $user = new User();
                        $user->initUserByRow($row);
                        $users[] = $user;
                    }
                    
                    $href = new Href();
        		    $href->class = "btn";
                    $href->label = $labelsAdmin->get("crud_addElement");
                    $href->class = "btn btn-primary";
                    $href->link = $_SERVER["SCRIPT_NAME"] . "?a=add";
                    $href->icon = "add";
                    $href->style = "margin-bottom:20px;";
                    $html .= $href->getHtml();
                    
                    if ($users) {
                        
                        
                        $html .= "<table class='table table-striped'>";
                        $html .= "<thead><tr><th>Nome</th><th>Nome de Utilizador</th><th>Email</th><th>Tipo</th><th>Nutricionista</th><th></th></tr></thead>";
                        
                        foreach($users as $user) {
                            $html .= "<tr>";
                            
                            $html .= "<td>" . $user->name . "</td>";
                            $html .= "<td>" . $user->username. "</td>";
                            $html .= "<td>" . $user->email . "</td>";
                            $html .= "<td>" . $user->getTipo() . "</td>";
                            $html .= "<td>";

                            if ($user->idnutritionist) {
                                
                                $nutricionist = new User();
                                $nutricionist->initUserById($user->idnutritionist);
                                $html .= $nutricionist->name;
                            }
                            $html .= "</td>";
                            
                            
                            // Actions
                            $html .= "<td>";
                            
                            // Edit
                            $href = new Href();
                            $href->label = $labelsAdmin->get("crud_editElement");
                            $href->class = "btn btn-primary";
                            $href->link = $_SERVER["SCRIPT_NAME"] . "?a=edit&key=" . $user->key;
                            $href->icon = "page-white-edit";
                            $html .= $href->getHtml();
                            
                            if ($loggedUser->idtipo != 'N') {
                    	     
                                // Delete
                                $href = new Href();
                    		    $href->class = "btn";
                                $href->label = $labelsAdmin->get("crud_deleteElement");
                                $href->action = "deleteUser";
                                $href->parameter = $user->key;
                                $href->icon = "delete";
                                $href->confirmation = true;
                                $html .= $href->getHtml();
                                
                    	    }
                    	    
                            $html .= "</td>";
                            $html .= "</tr>";
                        }
                        
                        $html .= "</table>";
                    }
                }
            }
            
            return $html;
        }
        
        
        /**
         * Show users
         */
        public function show($users, $options = null)
        {
            
            foreach($users as $user) {
                if ($user->photo) {
                    $html .= "<div class='album";
                    $html .= "'>";
                    
                    // Photo
                    $html .= "<div class='photo'>";
                    
                    $html .= "<a href='" . $user->getUrl() . "'>";
                    $html .= $user->photo->display("medium");
                    $html .= "</a>";
                    
                    $html .= "</div>";
                    
                    // User
                    $html .= "<div class='albumUser'>";
                    
                    $html .= "<div class='albumLines'>";
                        // First Line
                        $html .= "<div class='firstLine clearfix'>";
                            if (!$options["noflag"]) {
                                $country = new Country($user->country);
                                if ($country->id) {
                                    $html .= "<div class='country'>";
                                    $html .= "<img src='" . Settings::$domainAdmin . "images/flags/" . strtolower($country->id) . ".png' class='flag' title='" . $country->name . "'>";
                                    $html .= "</div>";
                                }
                            }
                            
                            $html .= "<div class='name'><a href='" . $user->getUrl() . "'>" . $user->getDisplayName() . "</a></div>";
                            
                            $html .= "<div class='icon'>" . $user->getIcon() . "</div>";
                            $html .= "<div class='age'>" . $user->age . "</div>";
                            
                            if ($isOnline) {
                                $html .= "<div class='userIsOnline'>" . $labels->get("user_isOnline") . "</div>";
                            }
                        $html .= "</div>";
                        
                    $html .= "</div>";
                        
                    $html .= "</div>";
                    
                    $html .= "</div>"; // End album
                }
            }
            
            
            return $html;
        }
        
        /**
         * Get the form to add or edit an user
         */
        public function getFormEdit($key)
        {
            global $labelsAdmin, $labels, $base, $loggedUser;
            
            $form = new Form("formUsers");
            $form->class = "form-horizontal";
            
            if ($key) {
                $user = new User();
                $user->initUserByKey($key);
                
                
                $form->legend = $labelsAdmin->get("crud_editElementTitle");
            } else {
                $form->legend = $labelsAdmin->get("crud_addElementTitle");
            }
            $form->ajax = true;
            
            // Key        
            $field = new FormFieldHidden("key");
            $field->value = $user->key;
            $field->label = "key";
            $form->addField($field);
            
            
    
            // Name
            $field = new FormField("name");
            $field->label = "Nome";
            $validator = new FormValidator();
            $validator->validateRequired = true;
            $field->validator = $validator;
            if ($user) {
                $field->value = $user->name;
            }
            $form->addField($field);
            
            
            // Email
            $field = new FormField("email");
            $field->label = "Email";
            $validator = new FormValidator();
            //$validator->validateRequired = true;
            if (!$key) {
                $validator->validateUnique = true;
                $validator->validateUniqueTable = 'cm_users';
                $validator->validateUniqueField = 'email';
            }
            $validator->validateEmail= true;
            $field->validator = $validator;
            if ($user) {
                $field->value = $user->email;
            }
            $form->addField($field);
            
            // Username
            $field = new FormField("username");
            $field->label = "Nome de utilizador";
            $validator = new FormValidator();
            $validator->validateRequired = true;
            
            $validator->validateMinLength = 3;
            $validator->validateMaxLength = 25;
            $validator->validateUnique = true;
            $validator->validateUniqueTable = 'cm_users';
            $validator->validateUniqueField = 'username';
            $field->validator = $validator;
            if ($user) {
                $field->value = $user->username;
            }
            $form->addField($field);
            
            
            // Password
            $field = new FormField("password");
            $field->label = "Password";
            if (!$key) {
                $validator = new FormValidator();
                $validator->validateRequired = true;
                $field->validator = $validator;
            } else {
                $field->notes = "Preencher se pretender alterar";
            }
            $form->addField($field);
            
            
            // Data Nascimento
            $field = new FormFieldDate("dateOfBirth");
            $field->label = "Data de Nascimento";
            $validator = new FormValidator();
            //$validator->validateRequired = true;
            $field->validator = $validator;
            if ($user) {
                $field->value = $user->dateOfBirth;
            }
            $form->addField($field);            
            
            // Address
            $field = new FormField("address");
            $field->label = "Morada";
            $validator = new FormValidator();
            //$validator->validateRequired = true;
            $field->validator = $validator;
            if ($user) {
                $field->value = $user->address;
            }
            $form->addField($field);
            
            // City
            $field = new FormField("city");
            $field->label = "Localidade";
            $validator = new FormValidator();
            //$validator->validateRequired = true;
            $field->validator = $validator;
            if ($user) {
                $field->value = $user->city;
            }
            $form->addField($field);
            
            // Job
            $field = new FormField("job");
            $field->label = "Profissão";
            $validator = new FormValidator();
            //$validator->validateRequired = true;
            $field->validator = $validator;
            if ($user) {
                $field->value = $user->job;
            }
            $form->addField($field);
            
            // Sex
            $field = new FormFieldRadios("sex");
            $sexRadios = array();
            $radio = new FormFieldRadio();
            $radio->description = $labels->get("global_sexMale");
            $radio->value = "M";
            $sexRadios[] = $radio;
            $radio = new FormFieldRadio();
            $radio->description = $labels->get("global_sexFemale");
            $radio->value = "F";
            $sexRadios[] = $radio;
            $validator = new FormValidator();
            $validator->validateRequired = true;
            $field->validator = $validator;
            $field->radios = $sexRadios;
            $field->label = $labels->get("global_sex");
            $field->value = $user->sex;
            $form->addField($field);
            
            // Height
            $field = new FormField("height");
            $field->label = "Altura (cm)";
            $validator = new FormValidator();
            $validator->validateNumeric = true;
            $field->validator = $validator;
            if ($user) {
                $field->value = $user->height;
            }
            $form->addField($field);
            
            // Notes
            $field = new FormField("notes");
            $field->type = "textarea";
            $field->label = "Notas";
            if ($user) {
                $field->value = $user->notes;
            }
            $form->addField($field);
            
            // Tipo
            $field = new FormFieldSelect("idtipo");
            $field->label = "Tipo";
            $validator = new FormValidator();
            $validator->validateRequired = true;
            $field->validator = $validator;
            $field->importTable = "cm_usersTypes";
            $field->importTableValue = "id";
            $field->importTableDisplay = "name_" . $base->language;
            
            switch ($loggedUser->idtipo) {
                case "N": 
                    $field->showEmpty = false;
                    $field->importTableFilter = " id IN ('P') ";
                    break;
            }
            if ($user) {
                $field->value = $user->idtipo;
            }
            $form->addField($field);
            
            // Id Nutricionista
            if ($loggedUser->admin) {
                $field = new FormFieldSelect("idnutritionist");
                $field->label = "Nutricionista";
                $validator = new FormValidator();
                //$validator->validateRequired = true;
                $field->validator = $validator;
                $field->importTable = "cm_users";
                $field->importTableValue = "id";
                $field->importTableDisplay = "name";
                $field->importTableFilter = " idtipo = 'N'";
                $field->value = $user->idnutritionist;
                $form->addField($field);
            }
            
            // Idgroup
            $field = new FormFieldSelect("idgroup");
            $field->label = "Grupo";
            $validator = new FormValidator();
            //$validator->validateRequired = true;
            $field->validator = $validator;
            $field->importTable = "groups";
            $field->importTableValue = "id";
            $field->importTableDisplay = "name_" . $base->language;
            if ($user) {
                $field->value = $user->idgroup;
            }
            //$form->addField($field);
            
            
            // Buttons
            $button = new FormButton();
            $button->name = "redirect";
            $button->type = "link";
            $button->link = "index.php";
            $button->label = $labelsAdmin->get("content_back");
            $button->icon = "arrow-left";
            $button->class = "btn";
            $form->addButton($button);

            $button = new FormButton();
            $button->name = "submit";
            $button->type = "submit";
            $button->label = $labelsAdmin->get("global_submit");
            $button->icon = "disk";
            $button->class = "btn btn-primary";
            $form->addButton($button);
            
            return $form;
        }
        
        /**
         * Get Pacientes
         */
        public function getPacientes()
        {
            $users = array();
            $database = new Database();
                    
            $txt = "SELECT * FROM cm_users ";
            $txt .= "WHERE idtipo = 'P' ";
            $txt .= "ORDER BY name";
            
            $database->sqlGet($txt);
            $users = array();
            while ($row = $database->sqlRow()) {
                $user = new User();
                $user->initUserByRow($row);
                $users[] = $user;
            }
                    
            return $users;
        }
     }