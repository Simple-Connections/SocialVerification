<?php

namespace SocialVerification\Verification;

use App\Entity\User;
use App\Repository\UserRepository;
use SocialVerification\Response\iSocialVerificationResponse;


use Symfony\Component\DependencyInjection\Annotation\Service;

/**
 * @Service
 */
class SocialTokenVerificationService
{
    public function __construct(
        private AppleVerificationService $appleVerificationService,
        private GoogleVerificationService $googleVerificationService,
        private FacebookVerificationService $facebookVerificationService,
        private UserRepository $userRepository,
    ) {}

    public function verifyAppleAuthCode(string $authCode, bool $mobile = true): iSocialVerificationResponse
    {
        return $this->appleVerificationService->verifyAuthToken($authCode, $mobile);
    }

    public function verifyGoogleJwt(string $googleToken): iSocialVerificationResponse
    {
        return $this->googleVerificationService->verifyAuthToken($googleToken);
    }

    public function verifyFacebookJwt(string $facebookToken): iSocialVerificationResponse
    {
        return $this->facebookVerificationService->verifyAuthToken($facebookToken);
    }
}