
## Instructions

Make sure to have the following domains pointing to the same source on your local server
- lernejo.test
- boszicht.test
- gazelle.test
- boszicht.lernejo.test
- gazelle.lernejo.test
- toverbol.lernejo.test

Add a database to your .ENV 

Instructions to get up and running:
- install dependencies `composer install`
- run migrations `php artisan migrate:fresh`
- seed the two test tenants `php artisan db:seed`

## Testing the application - problem at hand
Visit in your browser to each of the links above => it works.

Run the test in ´DomainTest´ => it fails.
