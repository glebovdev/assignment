<?php

namespace App\Models;

use Emarref\Jwt\Token\Payload;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;

class User implements Authenticatable, Authorizable
{
    use \Illuminate\Foundation\Auth\Access\Authorizable;
    use \Illuminate\Auth\Authenticatable;

    public $id;
    public $name;
    public $roles;
    public function __construct(Payload $payload)
    {
        $this->id = $payload->findClaimByName('sub')?->getValue();
        $this->name = $payload->findClaimByName('name')?->getValue() ?? '';
        $this->roles = $payload->findClaimByName('roles')?->getValue() ?? [];
    }

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

}
