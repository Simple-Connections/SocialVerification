<?php

namespace SocialVerification\Response;

class FacebookVerificationResponse implements iSocialVerificationResponse
{
    private $id;
    private $email;

    public function __construct(
        array $response,
    )
    {
        if ($response) {
            if (isset($response['id'])) {
                $this->id = $response["id"];
            }
            if (isset($response['email'])) {
                $this->email = $response['email'];
            }
        }
    }

    public function getEmail(): string { return $this->email; }

    public function getId(): string { return $this->id; }

}
