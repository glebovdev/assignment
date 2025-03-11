<?php

namespace App\Guards;

use App\Models\User;
use Emarref\Jwt\Algorithm\Hs256;
use Emarref\Jwt\Encryption\Factory;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Verification\Context;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;

class JwtGuard implements Guard
{
    protected ?Authenticatable $user = null;

    public function __construct(
        protected UserProvider $provider,
        protected Request      $request)
    {
    }

    public function check(): bool
    {
        return !is_null($this->user());
    }

    public function guest(): bool
    {
        return !$this->check();
    }

    public function user(): ?Authenticatable
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $token = $this->getTokenForRequest();
        if (!$token) {
            return null;
        }

        $jwt = new Jwt();
        $encryption = Factory::create(new Hs256(config('app.jwtkey')));
        $context = new Context($encryption);
        $jwt->deserialize($token);
        $this->user = new User($jwt->deserialize($token)->getPayload());
        $context->setSubject($this->user->getAuthIdentifier());
        try {
            $jwt->verify($jwt->deserialize($token), $context);
        }catch (\Exception $exception){
            return null;
        }

        return $this->user;
    }

    protected function getTokenForRequest(): ?string
    {
        $authHeader = $this->request->header('Authorization', '');
        if (str_starts_with($authHeader, 'Bearer ')) {
            return substr($authHeader, 7);
        }
        return null;
    }

    public function id(): ?string
    {
        if ($this->user()) {
            return $this->user()->getAuthIdentifier();
        }
        return null;
    }

    public function validate(array $credentials = []): bool
    {
        return false;
    }

    public function setUser(Authenticatable $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function hasUser(): bool
    {
        return !is_null($this->user());
    }
}
