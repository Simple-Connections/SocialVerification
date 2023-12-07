<?php

namespace SocialVerification\Response;

interface iSocialVerificationResponse
{
    public function getEmail(): string;
    public function getId(): string;
}