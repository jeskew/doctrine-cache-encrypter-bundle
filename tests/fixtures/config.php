<?php

$container->loadFromExtension('framework', array(
    'secret' =>  'Roger Kint is Keyser Soze.',
));

$container->register('key_factory', 'KeyFactory');

$container->register('my_public_key', 'Array')
    ->setPublic(false)
    ->setFactoryService('key_factory')
    ->setFactoryMethod('getCertificate');

$container->register('my_private_key', 'String')
    ->setPublic(false)
    ->setFactoryService('key_factory')
    ->setFactoryMethod('getPrivateKey');

$container->register('cache', 'Doctrine\Common\Cache\ArrayCache')
    ->addTag('cache.encrypted', [
        'password' => 'a',
        'cipher' => 'aes-256-cfb',
    ])
    ->addTag('cache.encrypted', [
        'alias' => 'my_encrypted_cache',
        'password' => 'b',
    ])
    ->addTag('cache.encrypted', [
        'alias' => 'my_pki_encrypted_cache',
        'certificate' => '@my_public_key',
        'key' => '@my_private_key',
        'cipher' => 'aes-192-ecb',
    ]);
