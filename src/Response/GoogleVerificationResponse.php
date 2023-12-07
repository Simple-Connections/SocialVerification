<?php

namespace SocialVerification\Response;

class GoogleVerificationResponse implements iSocialVerificationResponse
{
    private $id;
    private $email;

    public function __construct(
        array $response,
    )
    {
        if ($response) {
            
            if (isset($response['sub'])) {
                $this->id = $response["sub"];
            }
            if (isset($response['email'])) {
                $this->email = $response['email'];
            }
        }
    }

    public function getEmail(): string { return $this->email; }

    public function getId(): string { return $this->id; }

}