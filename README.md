##### Requirements
- This project was tested on PHP7.4 and MySQL 8

##### Installation
- `git clone`
- `composer install`
- `cp .env.example .env`
- update .env DB_* values
- `php artisan key:gen`
- `php artisan migrate --seed`

##### Tests
- You can check the tests to have an idea about the covered cases
- You can run the tests `./vendor/bin/phpunit`

##### Score Calculation Formula

- `score = strictMatchesCount + looseMatchesCount * 0.75`

##### The API endpoint
- After running `php artisan migrate --seed` you will have 20 properties (mostly ids from 1 to 20) and 2000 search profiles
- You can then start experimenting with API endpoint `{base_url}/api/match/{property-id}`

##### Where to find the code of the solution
- You will find most of the code written under the namespace `App\Matcher` besides the controller and the tests.
 
