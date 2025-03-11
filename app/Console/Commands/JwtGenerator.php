<?php
namespace App\Console\Commands;

use Emarref\Jwt\Algorithm\Hs256;
use Emarref\Jwt\Claim\PrivateClaim;
use Emarref\Jwt\Claim\Subject;
use Emarref\Jwt\Encryption\Factory;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Token;
use Illuminate\Console\Command;

class JwtGenerator extends Command
{
    protected $signature = 'jwt {id} {--no-rater}';

    protected $description = 'Generate a JWT token';

    public function handle(): void
    {
        fake()->seed($this->argument('id'));

        $roles = [];
        if (! $this->option('no-rater')) {
            $roles[] = 'rater';
        }

        $token = new Token();
        $token->addClaim(new Subject($this->argument('id')));
        $token->addClaim(new PrivateClaim('name', fake()->name()));
        $token->addClaim(new PrivateClaim('roles', $roles));

        $encryption = Factory::create(new Hs256(config('app.jwtkey')));
        $serializedToken = (new Jwt())->serialize($token, $encryption);

        $this->line($serializedToken);
    }
}
