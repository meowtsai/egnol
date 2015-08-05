<?php
$apiConfig = array(
    // True if objects should be returned by the service classes.
    // False if associative arrays should be returned (default behavior).
    'use_objects' => false,
  
    // The application_name is included in the User-Agent HTTP header.
    'application_name' => '',

    // OAuth2 Settings, you can get these keys at https://code.google.com/apis/console
    'oauth2_client_id' => 'kb6e6nibapfp5r9lf220vrbt76jj1ttn.apps.googleusercontent.com',
    'oauth2_client_secret' => '-C6cRJt1mv4yfkuwfnOa-1Uf',
    'oauth2_redirect_uri' => 'http://game.longeplay.com.tw/member/login_callback/google',

    // The developer key, you get this at https://code.google.com/apis/console
    'developer_key' => 'AIzaSyDHvc5rGr0qExSXmBuFDdEDWxIkWDHzPtY',
  
    // Site name to show in the Google's OAuth 1 authentication screen.
    'site_name' => 'LongE Play',

    // Which Authentication, Storage and HTTP IO classes to use.
    'authClass'    => 'Google_OAuth2',
    'ioClass'      => 'Google_CurlIO',
    'cacheClass'   => 'Google_FileCache',

    // Don't change these unless you're working against a special development or testing environment.
    'basePath' => 'https://www.googleapis.com',

    // IO Class dependent configuration, you only have to configure the values
    // for the class that was configured as the ioClass above
    'ioFileCache_directory'  =>
        (function_exists('sys_get_temp_dir') ?
            sys_get_temp_dir() . '/Google_Client' :
        '/tmp/Google_Client'),

    // Definition of service specific values like scopes, oauth token URLs, etc
    'services' => array(
      'analytics' => array('scope' => 'https://www.googleapis.com/auth/analytics.readonly'),
      'calendar' => array(
          'scope' => array(
              "https://www.googleapis.com/auth/calendar",
              "https://www.googleapis.com/auth/calendar.readonly",
          )
      ),
      'books' => array('scope' => 'https://www.googleapis.com/auth/books'),
      'latitude' => array(
          'scope' => array(
              'https://www.googleapis.com/auth/latitude.all.best',
              'https://www.googleapis.com/auth/latitude.all.city',
          )
      ),
      'moderator' => array('scope' => 'https://www.googleapis.com/auth/moderator'),
      'oauth2' => array(
          'scope' => array(
              'https://www.googleapis.com/auth/userinfo.profile',
              'https://www.googleapis.com/auth/userinfo.email',
          )
      ),
      'plus' => array('scope' => 'https://www.googleapis.com/auth/plus.login'),
      'siteVerification' => array('scope' => 'https://www.googleapis.com/auth/siteverification'),
      'tasks' => array('scope' => 'https://www.googleapis.com/auth/tasks'),
      'urlshortener' => array('scope' => 'https://www.googleapis.com/auth/urlshortener')
    )
);
?>