<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => 'mysql',//env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],
        
        //old 2023
        'mysql' => [
            'driver' => 'mysql',
            'host' => 'localhost',//env('DB_HOST', 'localhost'),
            //'port' => env('DB_PORT', '3306'),
            'database' => 'cromag8l_leadsedge',//env('DB_DATABASE',  'cc_center_manager'),
            'username' => 'cromag8l_leadsdg',//env('DB_USERNAME', 'root'),
            'password' => 'cromag8l_leadsedge',//env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            'dump_command_path' => '/opt/mysql/bin', 
            'options' => [
                PDO::ATTR_PERSISTENT => false //@todo
            ]
        ],
        
        
        //new 2023
        // 'mysql' => [
        //     'driver' => 'mysql',
        //     'host' => 'localhost',//env('DB_HOST', 'localhost'),
        //     //'port' => env('DB_PORT', '3306'),
        //     'database' => 'abgyasgw__leadsedge',//env('DB_DATABASE',  'cc_center_manager'),
        //     'username' => 'abgyasgw_leadpitch',//env('DB_USERNAME', 'root'),
        //     'password' => 'abgyasgw_leadpitch',//env('DB_PASSWORD', ''),
        //     'charset' => 'utf8',
        //     'collation' => 'utf8_unicode_ci',
        //     'prefix' => '',
        //     'strict' => false,
        //     'engine' => null,
        //     'dump_command_path' => '/opt/mysql/bin', 
        //     'options' => [
        //         PDO::ATTR_PERSISTENT => false //@todo
        //     ]
        // ],
 
     /*   'mysql2' => [
            'driver' => 'mysql',
           'host' => '45.113.122.178', //leadsedge
        //'host' => '103.53.40.64',	//cromacampus	
			//env('DB_HOST', 'localhost'),103.53.40.64
            //'port' => env('DB_PORT', '3306'),
             'port' =>  '3306',
            'database' => 'leadsmj2_leadsedge', 
            'username' => 'leadsmj2_leadsed', 
            'password' => 'leadsmj2_leadsedge', 

		//	'database' => 'cromag8l_grewbox', 
         // 'username' => 'cromag8l_grewbox', 
       // 'password' => 'cromag8l_grewbox', 
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            
        ],*/
        
        'mysql3' => [
            'driver' => 'mysql',
          // 'host' => '45.113.122.178', //leadsedge
         'host' => 'localhost',	//cromacampus	
			//env('DB_HOST', 'localhost'),103.53.40.64
            //'port' => env('DB_PORT', '3306'),
             'port' =>  '3306',
            'database' => 'cromag8l_fees', 
            'username' => 'cromag8l_fees', 
            'password' => 'cromag8l_fees@123#', 
		//	'database' => 'cromag8l_grewbox', 
         // 'username' => 'cromag8l_grewbox', 
       // 'password' => 'cromag8l_grewbox', 
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            
        ],
        
        'mysql4' => [
            'driver' => 'mysql',
           'host' => '162.241.65.30',	//cromacampus vps
            //'host' => 'localhost',	//cromacampus vps
             'port' =>  '3306',
           	'database' => 'cromavps_website',//env('DB_DATABASE', 'cc_center_manager'),
           'username' => 'cromavps_website',//env('DB_USERNAME', 'root'),
            'password' => 'cromavps_website#&123',//env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            
        ],
        
        
        
       /*  'mysql5' => [
            'driver' => 'mysql',
          // 'host' => '45.113.122.178', //leadsedge
        'host' => '103.53.40.64',	//cromacampus	
			//env('DB_HOST', 'localhost'),103.53.40.64
            //'port' => env('DB_PORT', '3306'),
             'port' =>  '3306',
            'database' => 'cromag8l_employment',//env('DB_DATABASE', 'cc_center_manager'),
             'username' => 'cromag8l_employm',//env('DB_USERNAME', 'root'),
              'password' => 'cromag8l_employment',//env('DB_PASSWORD', ''),

		//	'database' => 'cromag8l_grewbox', 
         // 'username' => 'cromag8l_grewbox', 
       // 'password' => 'cromag8l_grewbox', 
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            
        ],
        */
        
        
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'cluster' => false,

        'default' => [
            'host' => env('REDIS_HOST', 'localhost'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
