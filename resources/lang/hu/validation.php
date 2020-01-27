<?php
/**
 * User: subesz
 * Date: 2020. 01. 27.
 * Time: 22:25
 */

return [
    'custom' => [
        'email' => [
            'required' => 'Az e-mail cím megadása kötelező!',
            'unique' => 'Ez az e-mail cím már szerepel az adatbázisban!'
        ],
        'phone' => [
            'required' => 'A telefonszám megadása kötelező!',
            'unique' => 'Ez a telefonszám már szerepel az adatbázisban!'
        ]
    ]
];