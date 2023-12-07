<?php

namespace SocialVerification\Verification;

use SocialVerification\Response\GoogleVerificationResponse;

class GoogleVerificationService implements iSocialVerificationService
{
    public function verifyAuthToken(string $googleToken, bool $mobile = true): GoogleVerificationResponse
    {
        $url = "https://oauth2.googleapis.com/tokeninfo?id_token=$googleToken";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result, true);

        if (isset($result["error"])) {
            throw new \Exception($result["error"] . ": " . $result["error_description"]);
        }

        return new GoogleVerificationResponse($result);
    }
}
