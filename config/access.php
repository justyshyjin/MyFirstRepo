<?php
return [
        
    /*
     |-------------------------------------------
     | Access for each modules and their routes
     |-------------------------------------------
     |
     | The following are the modules and their respective permissions
     |
     */
    'modules' => [
        'Video Management' => [
            'Videos' => [
                'name' => 'Manage Videos',    
                'description' => 'Video add, edit, delete',
                'permission' => [
                    'All Access' => 'Videos_all',
                    'Add & Edit' => 'Videos_addedit',
                    'Edit Read Only' => 'Videos_editreadonly',
                    'Read Only' => 'Videos_readonly',
                    'Delete' => 'Videos_delete' 
                ],
            ]
        ],
        'User Management' => [
            'Users' => [
                'name' => 'Manage Users',    
                'description' => 'User add, edit, delete',
                'permission' => [
                    'All Access' => 'Users_all',
                    'Add & Edit' => 'Users_addedit',
                    'Edit Read Only' => 'Users_editreadonly',
                    'Read Only' => 'Users_readonly',
                    'Delete' => 'Users_delete' 
                ],
            ]
        ],
        'User Groups Management' => [
            'Users' => [
                'name' => 'Manage User Groups',    
                'description' => 'User Groups add, edit, delete',
                'permission' => [
                    'All Access' => 'UserGroups_all',
                    'Add & Edit' => 'UserGroups_addedit',
                    'Edit Read Only' => 'UserGroups_editreadonly',
                    'Read Only' => 'UserGroups_readonly',
                    'Delete' => 'UserGroups_delete' 
                ],
            ]
        ],
        'Settings' => [
            'Settings' => [
                'name' => 'Admin Settings',
                'description' => 'Settings edit, update.',
                'permission' => [
                    'All Access' => 'Settings_all',
                    'Read Only' => 'Settings_readonly',
                ],
            ],
        ]
    ],
    'permissionRoutes' => [
        'Videos_all' => [
            'VideoController@getIndex',
            'VideoController@getGridlist',
            'VideoController@getDetailsVideoEdit',
            'VideoController@getViewDetailsVideo',
            'VideoController@getGrid',
        ],
        'Settings_all' => [
            'SettingsController@getIndex',
            'SettingsController@postUpdate',
        ],
        'Settings_readonly' => [
            'SettingsController@getIndex',
        ],
        'Users_all' => [
            'AdminUserController@getIndex',
            'AdminUserController@getInfo',
            'AdminUserController@getChangePasswordInfo',
            'AdminUserController@postChangepassword',
            'AdminUserController@postAdd',
            'AdminUserController@postEdit',
            'AdminUserController@postDeleteProfileImage',
            'AdminUserController@getEdit',
            'AdminUserController@getUpdategridview',
            'AdminUserController@getUnique',
            'AdminUserController@postProfileImage',
            'AdminUserController@getAdd',
            'AdminUserController@getEdit',
            'AdminUserController@postUpdate',
            'AdminUserController@getDestroy',
            'AdminUserController@postAction',
            'AdminUserController@getChangepassword',
            'AdminUserController@getProfile',
            'AdminUserController@postProfile',
            'AdminUserController@getUnique',
            'AdminUserController@getGrid',
            'AdminUserController@getGridlist',
            'AdminUserController@getLogout',
        ],
        'Users_editreadonly' => [
            'AdminUserController@getIndex',
            'AdminUserController@getEdit',
            'AdminUserController@postUpdate',
            'AdminUserController@getUnique',
            'AdminUserController@getGrid',
            'AdminUserController@getGridlist',
            'AdminUserController@postRecords',
        ],
        'generalAccess' => [
            'DashboardController'
        ]    
    ],
];