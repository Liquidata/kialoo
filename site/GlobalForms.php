<?php



    // **********************************************************
    //  Form Login
    // **********************************************************
    $factoryFormLogin = new Form("formLogin");
    $factoryFormLogin->class = "form-horizontal";
    $factoryFormLogin->legend = $labels->get("login_title");
    $factoryFormLogin->ajax = true;
    $factoryFormLogin->hideJQuery = true;

    $field = new FormField("inputEmail");
    $validator = new FormValidator();
    $validator->validateRequired = true;
    $validator->validateEmail = true;
    $field->validator = $validator;
    $field->label = $labels->get("global_email");
    $field->enter = "submit";
    $factoryFormLogin->addField($field);

    $field = new FormField("inputPassword");
    $validator = new FormValidator();
    $validator->validateRequired = true;
    $field->validator = $validator;
    $field->type = "password";
    $field->label = $labels->get("global_password");
    $field->enter = "submit";
    $factoryFormLogin->addField($field);

    $button = new FormButton();
    $button->name = "submit";
    $button->type = "submit";
    $button->label = $labels->get("global_submit");
    $button->icon = "tick";
    $factoryFormLogin->addButton($button);

    $button = new FormButton();
    $button->name = "cancel";
    $button->type = "button";
    $button->jQuery = "setTimeout($.unblockUI, 0); ";
    $button->label = $labels->get("global_cancel");
    $button->icon = "cross";
    $factoryFormLogin->addButton($button);

    $forms["login"] = $factoryFormLogin;


    // **********************************************************
    //  Resend activation
    // **********************************************************
    $factoryFormResendActivation = new Form("formResendActivation");
    $factoryFormResendActivation->class = "form-horizontal";
    $factoryFormResendActivation->legend = $labels->get("resendActivation_legend");
    $factoryFormResendActivation->ajax = true;

    $field = new FormField("inputEmail");
    $validator = new FormValidator();
    $validator->validateEmail = true;
    $validator->validateRequired = true;
    $validator->validateMustExist = true;
    $validator->validateMustExistTable = 'cm_users';
    $validator->validateMustExistField = 'email';
    $field->error["param"] = "inputEmail";
    $field->validator = $validator;
    $field->label = $labels->get("global_email");
    $field->enter = "submit";
    $factoryFormResendActivation->addField($field);

    $button = new FormButton();
    $button->name = "submit";
    $button->type = "submit";
    $button->label = $labels->get("global_submit");
    $button->icon = "accept";
    $factoryFormResendActivation->addButton($button);

    $forms["resendActivation"] = $factoryFormResendActivation;

    // **********************************************************
    //  Recover Password
    // **********************************************************
    $factoryFormRecoverPassword = new Form("formRecoverPassword");
    $factoryFormRecoverPassword->class = "form-horizontal";
    $factoryFormRecoverPassword->legend = $labels->getTitle("recoverPassword_title");
    $factoryFormRecoverPassword->ajax = true;
    $factoryFormRecoverPassword->hideJQuery = true;

    $field = new FormField("inputEmail");
    $validator = new FormValidator();
    $validator->validateEmail = true;
    $validator->validateRequired = true;
    $validator->validateMustExist = true;
    $validator->validateMustExistTable = 'cm_users';
    $validator->validateMustExistField = 'email';
    $field->validator = $validator;
    $field->label = $labels->get("global_email");
    $field->enter = "submit";
    $field->placeholder = $labels->get("email_placeholder");
    $factoryFormRecoverPassword->addField($field);

    $button = new FormButton();
    $button->name = "submit";
    $button->type = "submit";
    $button->label = $labels->get("global_submit");
    $button->icon = "accept";
    $factoryFormRecoverPassword->addButton($button);

    $button = new FormButton();
    $button->name = "cancel";
    $button->type = "button";
    $button->jQuery = "setTimeout($.unblockUI, 0); ";
    $button->label = $labels->get("global_cancel");
    $button->icon = "cross";
    $factoryFormRecoverPassword->addButton($button);

    $forms["recoverPassword"] = $factoryFormRecoverPassword;


    // **********************************************************
    //  Change Password
    // **********************************************************
    $factoryFormChangePassword = new Form("formChangePassword");
    $factoryFormChangePassword->class = "form-horizontal col-md-5";
    $factoryFormChangePassword->ajax = true;

    $field = new FormFieldHidden("email");
    if (!$factoryFormChangePassword->submited) {
        $field->value = $_REQUEST["email"];
    }
    $factoryFormChangePassword->addField($field);

    $field = new FormField("inputPassword");
    $validator = new FormValidator();
    $validator->validateRequired = true;
    $validator->validateMinLength = 6;
    $validator->validateMaxLength = 30;
    $validator->validateEqualsTo = "inputVerification";
    $field->validator = $validator;
    $field->type = "password";
    $field->label = $labels->get("global_password");
    $factoryFormChangePassword->addField($field);

    $field = new FormField("inputVerification");
    $validator = new FormValidator();
    $validator->validateRequired = true;
    $field->validator = $validator;
    $field->type = "password";
    $field->label = $labels->get("global_repeatPassword");
    $factoryFormChangePassword->addField($field);


    $button = new FormButton();
    $button->name = "submit";
    $button->type = "submit";
    $button->label = $labels->get("global_submit");
    $button->icon = "disk";
    $button->class = "btn btn-primary";
    $factoryFormChangePassword->addButton($button);

    $forms["changePassword"] = $factoryFormChangePassword;
