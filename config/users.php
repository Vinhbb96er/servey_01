<?php

return [
    'avatar_default' => 'default_user.png',
    'avatar_path' => 'public/uploads/images',
    'password_default' => '123123',
    'level' => [
        'user' => 0,
        'admin' => 1,
        'supperadmin' => 2,
    ],
    'status' => [
        'block' => 0,
        'active' => 1,
    ],
    'gender' => [
        'male' => 1,
        'female' => 2,
        'other_gender' => 3,
    ],
    'name_length_default' => 9,
    'undentified_name' => 'Undentified name',
    'undentified_email' => 'Undentified email',
    'register_success' => true,
    'register_fail' => false,
    'email_user_not_exist' => true,
    'send_mail_reset_password_success' => true,
];
