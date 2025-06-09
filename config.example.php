<?php

    use League\OAuth2\Client\Token\AccessToken;
    $clientId = 'integration_id'; 
    $clientSecret = 'secret_key'; 
    $subdomain = 'subdomain.amocrm.ru'; 

    $accessToken = new AccessToken([
        'access_token' => 'longLivedAccessToken',
    ]); 