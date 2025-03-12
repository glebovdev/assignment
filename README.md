# The movie service

This service allows users that have the rater to set a rating from 1 to 5 and add a comment to movies in the database.
They can do this by sending a PUT request to `/movie/1/rating` with `{"rating": 4, "comment":"this is a comment"}` as body to give a rating for the first movie.

## setup
Use `php artisan migrate --seed` to set up the database.

## Authentication
Users get a jwt from a different service. They use the jwt to make authenticated requests different services.
You can use `php artisan jwt {id}` to create a jwt the auth service would provide.

## Docker Setup

The application is containerized for easy deployment in production.

### Environment Configuration

The project uses two separate environment files:

1. `.env.docker` - Contains environment variables for Docker Compose (database credentials, etc.)
2. `.env` - Used by Laravel for application configuration

This separation keeps Docker configuration distinct from application configuration.

You can modify `.env.docker` to change:
- Database credentials
- Docker-specific settings
- Container configuration

### Running with Docker

The project uses a Makefile to simplify Docker operations:

```bash
# Start the application
make up

# Stop the application
make down

# View logs
make logs

# Run migrations
make migrate

# Seed the database
make seed

# Access the container shell
make shell

# Show all available commands
make help
```

You can also manually run Docker commands:

```bash
docker-compose --env-file .env.docker up -d
```

This will:
- Build the Docker image with production settings
- Start the MySQL database
- Set up the application with database connection
- Run migrations automatically

## Production Features

The Docker container includes:

- PHP optimized with OPcache for better performance
- Proper file permissions for security
- Automatic database migration
- Environment variables for easy configuration

## Todo
* Users should only be able to add their rating once.
* All responses should be json. Even a 403 should not return html.
* Paginate /movie and /rating
* The /movie and /movie/{id} endpoints respond slow when there are a lot of ratings for a movie.
* Make sure the container can run in production
