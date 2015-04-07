<?php
    /**
	* Site
	* Global Functions to display content
	*
	* @copyright  Copyright (c) Liquidata. (http://www.liquidata.pt)
	*/
    class Site
    {

        /**
         * Starts specific variables of this site
         */
        public function start()
        {

        }


        /**
         * Replace [] tags
         */
        public function prepareLabel($text)
        {
            $result = $text;
            $result = str_replace("[sitename]", Settings::$siteName, $result);
            return $result;
        }

        public function getFormQuemDestina()
        {
            global $labels;

            $form = new Form("quemDestina");
            $form->ajax = true;

            // Nome
            $field = new FormField("nome");
            $field->label = $labels->get("profile_name");
            $validator = new FormValidator();
            $validator->validateRequired = true;
            $field->validator = $validator;
            $form->addField($field);

            // Email
            $field = new FormField("email");
            $field->label = $labels->get("global_email");
            $validator = new FormValidator();
            $validator->validateRequired = true;
            $validator->validateEmail = true;
            $field->validator = $validator;
            $field->placeholder = $labels->get("email_placeholder");
            $form->addField($field);

            // Localidade
            $field = new FormField("localidade");
            $validator = new FormValidator();
            $validator->validateRequired = true;
            $field->validator = $validator;
            $field->label = $labels->get("quemDestina_localidade");
            $form->addField($field);

            // Profissao
            $field = new FormField("profissao");
            $field->label = $labels->get("quemDestina_profissao");
            $form->addField($field);

            // Data Nascimento
            $field = new FormFieldDate("dataNascimento");
            $field->label = $labels->get("quemDestina_dataNascimento");
            $validator = new FormValidator();
            $validator->validateRequired = true;
            $field->validator = $validator;
            $form->addField($field);

            // Button Save
            $button = new FormButton();
            $button->name = "submit";
            $button->class = "btn btn-primary";
            $button->label = $labels->get("global_submit");
            $button->type = "submit";
            $button->icon = "email";
            $form->addButton($button);

            return $form;
        }

        public function getFormCalculoIMC()
        {
            global $labels;

            $form = new Form("calculoIMC");
            $form->ajax = true;

            // Peso
            $field = new FormFieldSlide("peso");
            $field->label = $labels->get("balanca_peso");
            $field->range = false;
            $field->min = 20;
            $field->max = 300;
            $field->initialMin = 75;
            $field->posUnit = " kg";
            $form->addField($field);

            // Altura
            $field = new FormFieldSlide("altura");
            $field->label = $labels->get("balanca_altura");
            $field->range = false;
            $field->min = 20;
            $field->max = 240;
            $field->initialMin = 140;
            $field->posUnit = " cm";
            //$field->separator = $labels->get("global_to");
            $form->addField($field);

            // Button Avancar
            $button = new FormButton();
            $button->name = "submit";
            $button->label = $labels->get("global_calculate");
            $button->type = "submit";
            $button->icon = "tick";
            $button->class = "btn btn-primary";
            $form->addButton($button);

            return $form;
        }

        public function getFormLocaisConsulta()
        {
            global $labels;

            $form = new Form("locaisConsulta");
            $form->ajax = true;
            $form->style = "width:600px;";

            // Farmácia
            $field = new FormField("nome");
            $field->label = $labels->get("site_farmacia");
            $field->value = $_REQUEST["nome"];
            $form->addField($field);

            // Concelho
            $field = new FormFieldChain("concelho");
            $field->importTable = "listaConcelhos";
    	    $field->importTableValue = "id";
    	    $field->importTableDisplay = "nome";
    	    $field->levels = 2;
            $field->labels = array($labels->get("site_distrito"), $labels->get("site_concelho"));
            $field->value = $_REQUEST["concelho"];
            $form->addField($field);

            // Button Submit
            $button = new FormButton();
            $button->name = "submit";
            $button->label = $labels->get("global_search");
            $button->type = "submit";
            $button->class = "btn btn-primary";
            $button->icon = "magnifier";
            $form->addButton($button);


            // Button Ver Todas
            $button = new FormButton();
            $button->name = "all";
            $button->label = $labels->get("site_verTodas");
            $button->type = "submit";
            $button->icon = "table";
            $form->addButton($button);

            return $form;
        }


        public function getFormContactos()
        {
            global $labels;

            $form = new Form("contactos");
            $form->ajax = true;

            // Nome
            $field = new FormField("nome");
            $field->label = $labels->get("profile_name");
            $validator = new FormValidator();
            $validator->validateRequired = true;
            $field->validator = $validator;
            $form->addField($field);

            // Email
            $field = new FormField("email");
            $field->label = $labels->get("global_email");
            $validator = new FormValidator();
            $validator->validateRequired = true;
            $validator->validateEmail = true;
            $field->validator = $validator;
            $field->placeholder = $labels->get("email_placeholder");
            $form->addField($field);

            // Assunto
            $field = new FormField("assunto");
            $field->label = $labels->get("contactos_assunto");
            $form->addField($field);

            // Mensagem
            $field = new FormField("mensagem");
            $field->htmlEditor = false;
            $field->type = "textarea";
            $field->label = $labels->get("contactos_mensagem");
            $field->style = "height:100px;";
            $validator = new FormValidator();
            $validator->validateRequired = true;
            $field->validator = $validator;
            $form->addField($field);


            // Button Save
            $button = new FormButton();
            $button->name = "submit";
            $button->class = "btn btn-primary";
            $button->label = $labels->get("global_submit");
            $button->type = "submit";
            $button->icon = "email";
            $form->addButton($button);

            return $form;
        }

        /**
         * Create the html to area Fitoterapia
         */
        public function getAreaFitoterapia($area, $contents)
        {
            switch($area) {
                case "plantas":
                    $tipo = 1;
                    break;
                case "marcas":
                    $tipo = 2;
                    break;
            }
            $result = "";
            $result .= "<div class='grupo grupo" . ucfirst($area) . "'>";
            $result .= "<div class='sidebar'><img src='/images/sidebar" . ucfirst($area) . ".png'></div>";

            $result .= "<div class='items'>";
            foreach($contents as $i => $content) {
                if ($content->getValue("tipo") == $tipo) {
                    if (!$content->getValue("enderecoExterno")) {
                        $result .= "<a href='?id=" . $content->id . "'>";
                    } else {
                        $result .= "<a target='_blank' href='" . $content->getValue("enderecoExterno") . "'>";
                    }

                    $result .= "<div class='item'>";
                        $result .= "<div class='titulos'>";
                        $result .= "<div class='titulo'>";

                        $result .= '<div style="margin:0 auto;display: table; height: 70px; overflow: hidden;">';
                        $result .= ' <div style="display: table-cell; vertical-align: middle;">';
                        $result .= '   <div>';
                        $result .= $content->getValue("titulo");
                        $result .= '   </div>';
                        $result .= "<div class='subtitulo subtitulo" . ucfirst($area) . "'>" . $content->getValue("subtitulo") . "</div>";
                        $result .= ' </div>';
                        $result .= '</div>';


                        $result .= "</div>";

                        $result .= "</div>";
                        $result .= "<div class='thumb'><img src='" . $content->getValue("thumbnail")->getUrl("full") . "'></div>";
                    $result .= "</div>";
                    $result .= "</a>";
                }
            }
            $result .= "</div>";
            $result .= "</div>";

            return $result;
        }

    }
