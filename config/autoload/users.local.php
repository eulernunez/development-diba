<?php
return array(
    // //Place it into main config folder ////
    'settings' => array(
        'BASE_URL' => 'http://zendskeleton.localhost',//http://example.com
        "EMAIL" => array(
            "SMTP_SENDER_TYPE" => "user",
            "SMTP_NAME" => "localhost",
            "SMTP_HOST" => "localhost",
            "SMTP_PORT" => "25",
            "SMTP_CONNECTION_CLASS" => "plain",
            "SMTP_USERNAME" => "",
            "SMTP_PASSWORD" => "",
            "SMTP_SSL" => "",
            "BODY" => "Hello there!",
            "FROM" => "FROM_URL",
            "TO" => "TO_URL",
            "MAIL_FROM_NICK_NAME" => "NICK_NAME",
            "SUBJECT" => "SUBJECT_OF_THE_MAIL",
            "FROM_NICK_NAME" => "NICK_NAME",
            'UPDATE_EMAIL_TEMPLATE' => true
        ),
        'FORGOT_PASSWORD_SUBJECT' => 'Reset Password Mail'
    ),
    'afterLoginURL' => 'application', //Change it where your application redirects after login
    'controllers' => array(
        'invokables' => array(
            'Users\Controller\User' => 'Users\Controller\UserController'
        )
    ),
    'whitelist' => array(
        '/users',
        '/users/index',
        '/users/logout',
        '/users/forgot-password',
        '/users/reset-password',
        '/users/password-reset-confirmation',
    )
);
