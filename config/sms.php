<?php

return [
    'sms' => [
        'nexmo' => [],
        'payamakYab' => [
            'username' => env('PAYAMAKYAB_USERNAME', 'seylaneh'),
            'password' => env('PAYAMAKYAB_PASSWORD', '2280025159'),
            'number'   => env('PAYAMAKYAB_NUMBER', '1000185'),
            'url'      => env('PAYAMAKYAB_WSDL', 'https://p.1000sms.ir/Post/Send.asmx?wsdl'),
        ],
    ],
];


