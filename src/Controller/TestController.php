<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class TestController extends AbstractController
{
    /**
     * @Route ("/test/operations", name="test_operations")
     */
    public function testEncryptDecrypt()
    {
        $data = '1234';
        $method = $this->getParameter('method');
        $firstKey = base64_decode($this->getParameter('first_key'));
        $secondKey = base64_decode($this->getParameter('second_key'));
        $ivLength = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $firstEncrypt = openssl_encrypt($data, $method, $firstKey, OPENSSL_RAW_DATA, $iv);
        $secondEncrypt = hash_hmac('sha3-512', $firstEncrypt, $secondKey, TRUE);
        $totalSecured = base64_encode($iv.$secondEncrypt.$firstEncrypt);
        dump($totalSecured);

        $decodedTotalSecured = base64_decode($totalSecured);
        $ivDecode = substr($decodedTotalSecured, 0, $ivLength);
        $secondEncrypted = substr($decodedTotalSecured, $ivLength, 64);
        $firstEncrypted = substr($decodedTotalSecured, $ivLength+64);

        $output = openssl_decrypt($firstEncrypted, $method, $firstKey, OPENSSL_RAW_DATA, $iv);
        $secondEncryptedRaw = hash_hmac('sha3-512', $firstEncrypted, $secondKey, TRUE);
        if (hash_equals($secondEncrypted, $secondEncryptedRaw)) {
            dump($output);
        } else {
            dump('ne');
        }
        dd();
    }

}