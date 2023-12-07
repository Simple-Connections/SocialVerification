<?php

namespace SocialVerification\Verification;

use SocialVerification\Response\iSocialVerificationResponse;

interface iSocialVerificationService
{
    public function verifyAuthToken(string $authToken, bool $mobile = true): iSocialVerificationResponse;
}
