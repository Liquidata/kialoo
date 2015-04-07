<?php
    /**
	* Atividades Fisicas
	* Functions about Atividades Fisicas
	*
	* @copyright  Copyright (c) Liquidata. (http://www.liquidata.pt)
	*/
    class AtividadesFisicas
    {
        /**
         * Get all atividades fisicas
         */
        public function getAllFilteredByUser()
        {
            global $base, $loggedUser;
            
	        // Create user atividades fisicas
            $list = $loggedUser->listAtividadeFisica();
        
            $atividadesFisicas = array();
          
            foreach($list as $item) {
                $atividadesFisicas[] = $item->getValue("atividade");
            }
            $result = array();
            
            $database = new Database();
            $txt = "SELECT * FROM atividadesFisicas ORDER BY description_" . $base->language;
            $database->sqlGet($txt);
            while($row = $database->sqlRow()) {
                $atividadeFisica = new AtividadeFisica();
                $atividadeFisica->id = $row["id"];
                $atividadeFisica->title = $row["title_" . $base->language];
                $atividadeFisica->description = $row["description_" . $base->language];
                $atividadeFisica->selected = in_array($row["id"], $atividadesFisicas);
                $result[] = $atividadeFisica;
            }
            return $result;
        }
        
        /**
         * Create the form to export the Atividades Fisicas of the logged user
         */
        public function getFormExport()
        {
            global $labels;
            
            $form = new Form("atividadesFisicas");
            $form->ajax = true;
            
            // Send by Email
            $button = new FormButton();
            $button->name = "submitEmail";
            $button->type = "submit";
            $button->label = $labels->get("site_atividadesFisicasSendEmail");
            $button->icon = "email-go";
            $button->class = "btn btn-primary";
            $form->addButton($button);
            
            
            // Send by PDF
            $button = new FormButton();
            $button->name = "submitPDF";
            $button->type = "link";
            $button->link = "atividade-fisica?pdf=1";
            $button->label = $labels->get("site_atividadesFisicasSendPDF");
            $button->icon = "report";
            $button->class = "btn";
            $form->addButton($button);
            
            
            
            return $form;
        }
        
        
    }
