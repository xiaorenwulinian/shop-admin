<?php

return [


    /**
     *lclapi 秘钥
     */
    'secret_key_lclapi' => env('FIREBASE_JWT_SECRET_KEY_LCLAPI', 'app_user_token'),

    /**
     *前后端分离后 后台管理 秘钥
     */
    'secret_key_backend' => env('FIREBASE_JWT_SECRET_KEY_BACKEND', 'backend_token'),

    /**
     *App ios / android  秘钥
     */
    'secret_key_app' => env('FIREBASE_JWT_SECRET_KEY_APP', 'app_token'),
    /**
     *lclapi 秘钥
     */
    'secret_key_mini_program' => env('FIREBASE_JWT_SECRET_KEY_MINI_PROGRAM', 'mini_program_token'),


    /**
     * token 过期时间 单位秒
     */
    'expire_time_lcl_api'       => env('FIREBASE_JWT_EXPIRE_TIME_LCLAPI', 60 * 60 ),
    'expire_time_backend'       => env('FIREBASE_JWT_EXPIRE_TIME_BACKEND', 60 * 60 ),
    'expire_time_app'           => env('FIREBASE_JWT_EXPIRE_TIME_APP', 60 * 60 ),
    'expire_time_mini_program'  => env('FIREBASE_JWT_EXPIRE_TIME_MINI_PROGRAM', 60 * 60 ),

];
