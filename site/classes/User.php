<?php
    /**
	* User
	* Functions about a user like CRUD
	*
	* @copyright  Copyright (c) Liquidata. (http://www.liquidata.pt)
	*/
    class User extends UserCore
    {
        
        function __construct($id = null) {
            parent::__construct($id);
        }
        
        
        /**
		 * Fill class attributes with row from database
		 */
		public function initUserByRow($row)
		{
		    parent::initUserByRow($row);
		    
		                
            // Admin
            if ($row["idtipo"] == "A") {
                $this->admin = true;
            }
            
            
		}
		
        /**
         * Get the type of user
         */
        public function getTipo()
        {
            switch ($this->idtipo) {
                case "A":
                    $result = "Administrador";
                    break;
                case "N":
                    $result = "Nutricionista";
                    break;
                case "P":
                    $result = "Paciente";
                    break;
            }
            return $result;
        }
        
        /**
         * Get the form to edit user fields
         */
        public function getFormDadosPessoais()
        {
            global $labels;
            
            $form = new Form("dadosPessoais");
            $form->ajax = true;
            
            // Nome        
            $field = new FormField("name");
            $field->label = $labels->get("profile_name");
            $validator = new FormValidator();
            $validator->validateRequired = true;
            $field->validator = $validator;
            $field->value = $this->name;
            $form->addField($field);
            
            // Email
            $field = new FormField("email");
            $field->label = $labels->get("global_email");
            $validator = new FormValidator();
            //$validator->validateRequired = true;
            $validator->validateEmail = true;
            $field->validator = $validator;
            $field->placeholder = $labels->get("email_placeholder");
            $field->value = $this->email;
            $form->addField($field);
            
            // Data Nascimento
            $field = new FormFieldDate("dateOfBirth");
            $field->label = "Data de Nascimento";
            $validator = new FormValidator();
            $validator->validateRequired = true;
            $field->validator = $validator;
            if ($user) {
                $field->value = $user->dateOfBirth;
            }
            $field->value = $this->dateOfBirth;
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
            $field->value = $this->address;
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
            $field->value = $this->city;
            $form->addField($field);
            
            // Job
            if ($this->idtipo == 'P') {
                $field = new FormField("job");
                $field->label = "Profissão";
                $validator = new FormValidator();
                //$validator->validateRequired = true;
                $field->validator = $validator;
                if ($user) {
                    $field->value = $user->job;
                }
                $field->value = $this->job;
                $form->addField($field);
            }
            
            // Password
            $field = new FormField("password");
            $field->label = "Password";
            $field->type = "password";
        
            $validator = new FormValidator();
            $validator->validateMinLength = 6;
            $validator->validateMaxLength = 30;
            $field->validator = $validator;
            $field->notes = $labels->get("site_passwordNotes");
            $form->addField($field);
            
            
            // Button Save
            $button = new FormButton();
            $button->name = "submit";
            $button->class = "btn btn-primary";
            $button->label = $labels->get("global_save");
            $button->type = "submit";
            $button->icon = "email";
            $form->addButton($button);
            
            return $form;
        }
        
        /**
         * Adiciona atividade fisica ao user
         */
        public function addAtividadeFisica($id)
        {
            $content = new Content();
            $content->idmenu = 19;
            $values = array();

            // Paciente
            $value = new Value();
            $value->idattribute = 48;
            $value->value = $this->id;
            $values[] = $value;
            
            // Atividade
            $value = new Value();
            $value->idattribute = 49;
            $value->value = $id;
            $values[] = $value;
            
            $content->values = $values;
            
            $content->save();
        }
        
        /**
         * Lista atividades fisicas do user
         */
        public function listAtividadeFisica()
        {   
            global $loggedUser;
            
            
            // Get Contents
            $menu = new Menu(19);
            
            $settings["filters"] = 
    	                array(
                            array(
                                "method" => "value",
                                "idattribute" => 48,
                                "value" => $this->id
                            )
                        );
                        
	        $contents = $menu->getContents($settings);
	        
            return $contents;
        }
        
        /**
         * Adiciona atividade fisica ao user
         */
        public function removeAtividadeFisica($id)
        {
            $list = $this->listAtividadeFisica();
            
            
            foreach($list as $content) {
                if ($content->getValue("atividade") == $id) {
                    $content->delete();
                    break;
                }
            }
        }
        
        /**
         * List Atividade fisica in JSON
         */
        public function getListAtividadeFisica() 
        {
            $i = -1;
            $list = $this->listAtividadeFisica();
            
            $listAtividadeFisica = array();
            foreach($list as $i => $item) {
                $atividadeFisica = new AtividadeFisica($item->getValue("atividade"));
                
                $listAtividadeFisica[] = array (
                                        "id" => $item->getValue("atividade"),
                                        "index" => ($i+1),
                                        "title" => $atividadeFisica->title,
                                        );
            }
            
            while ($i < 4) {
                $i++;
                $listAtividadeFisica[] = array (
                                        "id" => "",
                                        "index" => ($i+1),
                                        "title" => "",
                                        );
                
            }
            return $listAtividadeFisica;
        }
        
        /**
         * Set First Time Agreement
         */
        public function setAgree()
        {
            if ($this->id) {
                $database = new Database();
                $txt = "UPDATE cm_users SET agree = '" . $database->true . "' WHERE id = " . $this->id;
                $database->sqlExecute($txt);
            }
        }
    }
