<?php

namespace SimpleConnections\SocialVerification\Response;

use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Lcobucci\JWT\UnencryptedToken;

class AppleVerificationResponse implements iSocialVerificationResponse
{
    private $iss;
    private $aud;
    private $exp;
    private $iat;
    private $sub;
    private $email;
    private $email_verified;

    public function __construct(
        array $response,
    )
    {
        if (isset($response["error"])) {
            $errorDescription = '';
            if (isset($response['error_description'])) {
                $errorDescription = $response['error_description'];
            }
            throw new \Exception('AppleVerificationResponse Error | ' . $response["error"] . ": " . $errorDescription);
        }   

        $parser = new Parser(new JoseEncoder());

        try {
            $verifiedAppleToken = $parser->parse(
                $response["id_token"]
            );
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
            echo 'Oh no, an error: ' . $e->getMessage();
        }
        assert($verifiedAppleToken instanceof UnencryptedToken);

        $this->iss = $verifiedAppleToken->claims()->get('iss');
        $this->aud = $verifiedAppleToken->claims()->get('aud');
        $this->exp = $verifiedAppleToken->claims()->get('exp');
        $this->iat = $verifiedAppleToken->claims()->get('iat');
        $this->sub = $verifiedAppleToken->claims()->get('sub');;
        $this->email = $verifiedAppleToken->claims()->get("email");
        $this->email_verified = $verifiedAppleToken->claims()->get('email_verified');
    }

    public function getId(): string
    {
        return $this->sub;
    }

    public function getIss(): string
    {
        return $this->iss;
    }

    public function getAud(): string
    {
        return $this->aud;
    }

    public function getExp(): string
    {
        return $this->exp;
    }

    public function getIat(): string
    {
        return $this->iat;
    }

    public function getSub(): string
    {
        return $this->sub;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getEmailVerified(): string
    {
        return $this->email_verified;
    }

}
