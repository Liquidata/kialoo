<?php
    // Lists
    $crud = new Crud("cm_lists");
    $crud->title = "Lists";
    $crud->showPrimaryKey = true;
    $button = new CrudButton();
    $button->type = "link";
    $button->page = "listsItems.php";
    $button->field = "id";
    $button->fieldTranslation = "listid";
    $button->title = "Items";
    $crud->extraButtons[] = $button;
    $crud->start();
    $cruds[$crud->dictionary] = $crud;

    // List Items
    $crud = new Crud("cm_listsitems");
    $crud->title = "Items";
    $crud->showPrimaryKey = true;
    $button = new CrudButton();
    $button->type = "link";
    $button->page = "index.php";
    $button->title = "Back";
    $crud->extraButtons[] = $button;
    $crud->fields["listid"]->relationship
        = array(
                "table" => "cm_lists",
                "field" => "id",
                "fieldTitle" => "name_en",
                "ownField" => "listid",
                );
    $crud->start();
    $cruds[$crud->dictionary] = $crud;


    // Users
    $crud = new Crud("cm_users");
    $crud->title = "Users";
    $crud->showPrimaryKey = true;
    $crud->start();
    $cruds[$crud->dictionary] = $crud;

    // Countries
    $crud = new Crud("cm_countries");
    $crud->title = "Countries";
    $crud->setOperations(false, true, false);
    $crud->start();
    $cruds[$crud->dictionary] = $crud;

    // Permissions
    $crud = new Crud("cm_permissions");
    $crud->title = "Permissions";
    $crud->start();
    $cruds[$crud->dictionary] = $crud;

    /*
    // TemplatesAreas
    $crud = new Crud("templatesAreas");
    $crud->title = "TemplatesAreas";
    $crud->showPrimaryKey = true;
    $button = new CrudButton();
    $button->type = "link";
    $button->page = "index.php";
    $button->title = "Back";
    $crud->extraButtons[] = $button;
    $crud->fields["idtemplate"]->relationship
        = array(
                "table" => "templates",
                "field" => "id",
                "fieldTitle" => "name",
                "ownField" => "idtemplate",
                );
    $crud->fields["idarea"]->relationship
        = array(
                "table" => "areas",
                "field" => "id",
                "fieldTitle" => "name_en",
                "ownField" => "idarea",
                );
    $crud->start();
    $cruds[$crud->dictionary] = $crud;`
    */

    // Menus
    $crud = new Crud("cm_menus");
    $crud->title = "Menus";
    $crud->showPrimaryKey = true;
    $crud->fields["title_pt"]->name = "Nome";
    $crud->fields["description_pt"]->name = "Descrição";
    $crud->fields["content_pt"]->name = "Conteúdo";
    $crud->start();
    $cruds[$crud->dictionary] = $crud;

    // Categories
    $crud = new Crud("categories");
    $crud->title = "Tipos de Artigo";
    $crud->showPrimaryKey = false;
    $crud->fields["name_pt"]->name = "Designação";
    $crud->start();
    $cruds[$crud->dictionary] = $crud;

    // Collections
    $crud = new Crud("collections");
    $crud->title = "Coleções Chunks";
    $crud->showPrimaryKey = false;
    $crud->fields["name_pt"]->name = "Designação";
    $crud->start();
    $cruds[$crud->dictionary] = $crud;

    // Sizes
    $crud = new Crud("sizes");
    $crud->title = "Tamanhos Chunks";
    $crud->showPrimaryKey = false;
    $crud->fields["name_pt"]->name = "Tamanho";
    $crud->start();
    $cruds[$crud->dictionary] = $crud;


