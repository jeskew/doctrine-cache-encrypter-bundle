<?php

class KeyFactory
{
    /** @var string */
    private $key;
    /** @var string[] */
    private $certificates;

    public function __construct()
    {
        // create a new key pair
        $pKey = openssl_pkey_new();

        // extract the private key
        openssl_pkey_export($pKey, $this->key);

        // extract the public key
        $csr = openssl_csr_new([], $pKey);
        $x509 = openssl_csr_sign($csr, null, $pKey, 1);
        openssl_x509_export($x509, $certificate);

        $otherKey = openssl_pkey_new();

        $this->certificates = [
            $certificate,
            openssl_pkey_get_details($otherKey)['key'],
        ];

        // clean up the created artifacts
        openssl_x509_free($x509);
        openssl_pkey_free($pKey);
        openssl_pkey_free($otherKey);
    }

    public function getPrivateKey()
    {
        return $this->key;
    }

    public function getCertificates()
    {
        return $this->certificates;
    }
}
