<?php

use Jsq\Iron\Password;
use Symfony\Component\DependencyInjection\Reference;

$container->loadFromExtension('framework', array(
    'secret' =>  'Roger Kint is Keyser Soze.',
));

$container->register('key_factory', 'KeyFactory');

$container->register('my_public_key', 'Array')
    ->setPublic(false)
    ->setFactory([new Reference('key_factory'), 'getCertificate']);

$container->register('my_private_key', 'String')
    ->setPublic(false)
    ->setFactory([new Reference('key_factory'), 'getPrivateKey']);

$container->register('my_iron_password', Password::class)
    ->setPublic(false)
    ->setArguments([str_repeat('x', Password::MIN_LENGTH), 'password_id']);

$container->register('my_cache', 'Doctrine\Common\Cache\ArrayCache')
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
    ])
    ->addTag('cache.encrypted', [
        'iron' => true,
        'alias' => 'my_iron_cache',
        'password' => '@my_iron_password'
    ]);
