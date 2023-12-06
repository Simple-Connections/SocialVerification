<?php

namespace SimpleConnections\SocialVerification\Verification;

use SimpleConnections\SocialVerification\Response\AppleVerificationResponse;
use DateTimeImmutable;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder as TokenBuilder;

final class AppleVerificationService implements iSocialVerificationService
{
    public function __construct(
        private string $applePemKeyBase64,
        private string $kid,
        private string $teamId,
        private string $clientId,
        private string $bundleId,
    )
    {}

    public function verifyAuthToken(string $authCode, bool $mobile = true): AppleVerificationResponse
    {
        $tokenBuilder = (new TokenBuilder(new JoseEncoder(), ChainedFormatter::withUnixTimestampDates()));
        $algorithm = Sha256::create();

        if (!$this->applePemKeyBase64) {
            throw new \Exception('Signing Pem Key String is not set');
        }

        $clientId = $this->bundleId;
        if (!$mobile) {
            $clientId = $this->clientId;
        }

        $signingKey = InMemory::plainText(base64_decode($this->applePemKeyBase64));

        $now = new DateTimeImmutable();
        $token = $tokenBuilder
            // Configures the issuer (iss claim)
            ->issuedBy($this->teamId)
            // Configures the audience (aud claim)
            ->permittedFor('https://appleid.apple.com')
            // Configures the time that the token was issue (iat claim)
            ->issuedAt($now)
            // Configures the expiration time of the token (exp claim)
            ->expiresAt($now->modify('+10 minutes'))
            // Configures a new claim, called "uid"
            ->relatedTo($clientId)
            // Configures a new header, called "foo"
            ->withHeader('kid', $this->kid)
            // ->withHeader('alg', 'ES256')
            ->getToken($algorithm, $signingKey); // Retrieves the generated token

        $url = "https://appleid.apple.com/auth/token";

        $ch = curl_init();
        // set curl variables
        $postFields = [
            'client_id' => $clientId,
            'client_secret' => $token->toString(),
            'code' => $authCode,
            'grant_type' => 'authorization_code',
        ];

        curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded'
        ));
        $result = curl_exec($ch);
        curl_close($ch);

        return new AppleVerificationResponse(json_decode($result, true));
    }

}