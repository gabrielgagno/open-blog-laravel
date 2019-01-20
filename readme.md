# OPEN BLOG API

## Setup
```
composer install
cp .env.example .env //fill up the db credentials
```

## Data
Use either ```TestingDatabaseSeeder``` or ```LocalDatabaseSeeder``` to seed the database for testing. Use the following command:
```
php artisan db:seed --class=LocalDatabaseSeeder
```

## Laravel Passport
The application uses Laravel Passport for its application layer. Run the following to add credentials:
```
php artisan passport:install
```

## Testing
```
vendor/bin/phpunit
```

## Quick API Guide

## Notes
The request headers for API calls are the following:
```
Accept: application/json
Content-Type: application/x-www-form-urlencoded
Authorization: Bearer <key from access token endpoint>
```

### Authorization: Get Access Token
The application uses a password credentials grant.
```POST oauth/token```
Parameters:
* grant_type:password
* client_id:<generated client id in passport:install>
* client_secret:<generated client secret in passport:install>
* username:<check seeded database for user email>
* password:<check seeded database for user password>

## GET Posts
```GET /api/posts```
Query String parameters for filtering (all are optional):
* category
* published_date_from
* published_date_to
* status : in draft, published, archived

## POST Posts
```POST /api/posts```
Parameters:
* title : required
* body : required
* status: required, in (draft, published, archived)
* user_id : required
* tags
* category

## PUT Posts
```PUT /api/posts/:id```

URL Parameter:
* id - required

Parameters:
* title : required
* body : required
* status: required, in (draft, published, archived)
* user_id : required
* tags
* category

## DELETE Posts
```DELETE /api/posts/:id```

URL Parameter:
* id - required

## GET Users
```GET /api/users```

## POST Users
```POST /api/users```
Parameters:
* email : required
* password : required
* password_confirmation : required
* name : required
* role_id : required

## PUT Users
```PUT /api/users/:id```

URL Parameter:
* id - required

Parameters:
* email : required
* password : required
* password_confirmation : required
* name : required
* role_id : required

## DELETE USers
```DELETE /api/users/:id```

URL Parameter:
* id - required