<?php

namespace SocialVerification\Verification;

use SocialVerification\Response\FacebookVerificationResponse;

class FacebookVerificationService implements iSocialVerificationService
{
    public function verifyAuthToken(string $facebookToken, bool $mobile = true): FacebookVerificationResponse
    {
        $url = "https://graph.facebook.com/me?fields=id,name,email&access_token=$facebookToken";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result, true);

        if (isset($result["error"])) {
            throw new \Exception($result["error"]["message"]);
        }

        $facebookUserInfo = new self();
        $facebookUserInfo->id = $result['id'];
        $facebookUserInfo->email = $result['email'];

        return new FacebookVerificationResponse($result);
    }

}