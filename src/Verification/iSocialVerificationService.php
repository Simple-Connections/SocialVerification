<?php

namespace SimpleConnections\SocialVerification\Verification;

use SimpleConnections\SocialVerification\Response\iSocialVerificationResponse;

interface iSocialVerificationService
{
    public function verifyAuthToken(string $authToken, bool $mobile = true): iSocialVerificationResponse;
}
