# The movie service

This service allows users that have the rater to set a rating from 1 to 5 and add a comment to movies in the database.
They can do this by sending a PUT request to `/movie/1/rating` with `{"rating": 4, "comment":"this is a comment"}` as body to give a rating for the first movie.

## setup
Use `php artisan migrate --seed` to set up the database.

## Authentication
Users get a jwt from a different service. They use the jwt to make authenticated requests different services.
You can use `php artisan jwt {id}` to create a jwt the auth service would provide.

## Todo
* Users should only be able to add their rating once.
* All responses should be json. Even a 403 should not return html.
* Paginate /movie and /rating
* The /movie and /movie/{id} endpoints respond slow when there are a lot of ratings for a movie.
* Make sure the container can run in production