<?php

return [
        /*
         |--------------------------------------------------------------------------
         | payment package config
         |--------------------------------------------------------------------------
         |
         | This option determines the payment pakage configuration.
         |
         */
    'encode_decode_separator' => '_time',
    'vendor' => 'contus',
    'package' => 'payment',
        
    'ccavenue' => [
            'sandboxURL' => 'https://test.ccavenue.com/transaction/getRSAKey',
            'liveURL' => 'https://secure.ccavenue.com/transaction/getRSAKey',
            'merchantId' => 119630,
            'accessCode' => 'AVVV68DL53CH68VVHC',
            'workingKey' => '99E0696558EF366527FC04EF1910EE06',
            'ccavenueinittransaction'=>'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction',
            'testinittransaction'=>'https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction'
    ],
   
        
];