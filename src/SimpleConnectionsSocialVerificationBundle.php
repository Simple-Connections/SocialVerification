<?php 
namespace SocialVerification;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SimpleConnectionsSocialVerificationBundle extends AbstractBundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}