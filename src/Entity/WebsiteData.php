<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WebsiteDataRepository")
 */
class WebsiteData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="websites")
     */
    private $user;

    /**
     * @ORM\Column (type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @ORM\Column (type="string", length=255, nullable=false)
     */
    private $url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function encryptPassword(ParameterBagInterface $parameterBag): string
    {
        $password = $this->getPassword();
        $method = $parameterBag->get('method');
        $firstKey = base64_decode($parameterBag->get('first_key'));
        $secondKey = base64_decode($parameterBag->get('second_key'));
        $ivLength = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $firstEncrypt = openssl_encrypt($password, $method, $firstKey, OPENSSL_RAW_DATA, $iv);
        $secondEncrypt = hash_hmac('sha3-512', $firstEncrypt, $secondKey, TRUE);
        $encryptPassword = base64_encode($iv . $secondEncrypt . $firstEncrypt);

        return $encryptPassword;
    }

    public function decryptPassword(ParameterBagInterface $parameterBag): string
    {
        $password = $this->getPassword();
        $decodedPassword = base64_decode($password);
        $method = $parameterBag->get('method');
        $firstKey = base64_decode($parameterBag->get('first_key'));
        $secondKey = base64_decode($parameterBag->get('second_key'));
        $ivLength = openssl_cipher_iv_length($method);
        $ivDecode = substr($decodedPassword, 0, $ivLength);
        $secondEncrypted = substr($decodedPassword, $ivLength, 64);
        $firstEncrypted = substr($decodedPassword, $ivLength+64);

        $plainPassword = openssl_decrypt($firstEncrypted, $method, $firstKey, OPENSSL_RAW_DATA, $ivDecode);
        $secondEncryptedRaw = hash_hmac('sha3-512', $firstEncrypted, $secondKey, TRUE);
        if ($secondEncrypted != false && hash_equals($secondEncrypted, $secondEncryptedRaw)) {
            return $plainPassword;
        } else {
            return 'Неправилна парола';
        }
    }
}
