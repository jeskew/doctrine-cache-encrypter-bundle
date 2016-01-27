<?php

class KeyFactory
{
    /** @var string */
    private $key;
    /** @var string */
    private $certificate;

    public function __construct()
    {
        // create a new key pair
        $pKey = openssl_pkey_new();

        // extract the private key
        openssl_pkey_export($pKey, $this->key);

        // extract the public key
        $csr = openssl_csr_new([], $pKey);
        $x509 = openssl_csr_sign($csr, null, $pKey, 1);
        openssl_x509_export($x509, $this->certificate);

        // clean up the created artifacts
        openssl_x509_free($x509);
        openssl_pkey_free($pKey);
    }

    public function getPrivateKey()
    {
        return $this->key;
    }

    public function getCertificate()
    {
        return $this->certificate;
    }
}
