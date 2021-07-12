# Laravel API with JSON Web Token

API Endpoints:

1. Login

`/api/auth/login`: POST method.

Required post parameters `email` and `password`

2. Register

`/api/auth/register`: POST method.

Required post parameters `name`, `email` and `password`.

3. User (Authenticated)
   `api/auth/profile?token=VALID_TOKEN`: GET method

4. To view all tasks (Authenticated)
   `api/todos?token=VALID_TOKEN`: GET method

    **Description**: To view all tasks created by the logged in user

5. To create task (Authenticated)

    `api/todos?token=VALID_TOKEN`: POST method

    Required post parameters `title` and `details`.

6. To delete task (Authenticated)

    `api/todos?token=VALID_TOKEN`: DELETE method

7. To edit task (Authenticated)

    `api/todos?token=VALID_TOKEN`: PUT/PATCH method

    Required post parameters `title` and `details`.

8. To logout (Authenticated)

    `/api/auth/logout?token=VALID_TOKEN`: GET method

## Step 1: Install Laravel using Composer

```bash
laravel new laravel-api
```

Then navigate to `laravel-api` directory. Open this project in your favorite text editor or IDE like Visual Studio Code/vim/Sublime Text or PHPStorm.

## Step 2: Update Database credentials in environmental file

Now open the `.env` file and change following:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

## Step 3: Install JWT package

In command line interface (CLI) enter following command:

```bash
composer require tymon/jwt-auth:dev-develop --prefer-source
```

## Step 4: Publish the vendor

```bash
php artisan vendor:publish
```

It will copy jwt.php file from `/vendor/tymon/jwt-auth/config/config.php` to `/config` directory.

## Step 5: Generate JWT secret key

Enter the following command:

```bash
php artisan jwt:secret
```

## Step 6: Use jwt auth in `config/auth.php`

Open auth file from config directory and do necessary changes:

```php
<?php
    'defaults'         => [
        'guard'     => 'api',
        'passwords' => 'users',
    ],

    'guards'           => [

        ...

        'api' => [
            'driver'   => 'jwt',
            'provider' => 'users',
            'hash'     => false,
        ],
    ],
?>
```

## Step 7: Define routes for the api

Now we need to use some route for the api endpoinds. For this open `api.php` file inside the routes api:

```php
<?php

Route::group(
    [
        'middleware' => 'api',
        'namespace'  => 'App\Http\Controllers',
        'prefix'     => 'auth',
    ],
    function ($router) {
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');
        Route::post('logout', 'AuthController@logout');
        Route::get('profile', 'AuthController@profile');
        Route::post('refresh', 'AuthController@refresh');
    }
);

Route::group(
    [
        'middleware' => 'api',
        'namespace'  => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::resource('todos', 'TodoController');
    }
);
?>

```

## Step 8: Set up JWT authentication in User model

Now open the User model from the Models directory and add `implements` keyword after the `extend` keyword then use `JWTSubject` interface. And don't forget to import it.

```php
<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
/** ... **/

class User extends Authenticatable implements JWTSubject
{
?>
```

Now inside the User class add two required methods: `getJWTIdentifier` and `getJWTCustomClaims`:

```php
<?php
public function getJWTIdentifier() {
    return $this->getKey();
}

public function getJWTCustomClaims() {
    return [];
}
?>
```

## Step 9: Create AuthController

To create `AuthController` enter following command in CLI:

```bash
php artisan make:controller AuthController
```

Now if you navigate to `app/Http/Controllers` directory you will see a new file called `AuthController`. Open it and create a new public variable/property `$loginAfterSignUp` and set it to true.
Then make a method called `login`. Use Request class as an argument. Then inside the method define `$credentials` variable and set it to `$request->only('email', 'password')`.
Create another variable called `$token` and set it to null. Now use an if statement to check if `JWTAuth::attempt($credentials)` does match with `$token` or not. If it fails then we are showing a JSON response that the user is unauthenticated.
To use JWTAuth we need to import it. So we can write `use JWTAuth` or `use Tymon\JWTAuth\Facades\JWTAuth` on top of the file.

Now create another method called `register`. It will take Request class as an argument. We need to validate user inputs so that user don't forget to enter required details to register.
We also trying to validate unique email, so that email does not populate with more duplicate emails. For the password validation we are saying that the minimum length of the password should be 6 and maximum is 10.
After that we are instantiate User model with `$user` variable. Then we are assigning name, email and password property with request property. We are using `Hash` facades to encrypt user password and store it to database using `$user->save()` method.

Then we are checking if `$loginAfterSignUp` is truthy, if is it then we are returning the token from the login method.

And after that we are displaying a JSON response with status of true and the created user.

Next we are going to create a logout method with Request argument. In our logout method we are validating if token is present in the request, because we will be sending `token` parameters in our get method whenever we try to access authenticated API endpoints.
Then inside the try block we are invalidating our token then showing a JSON response saying that the user has been successfully logged out.

And inside the catch block if anything goes wrong we will show an error message that the user cannot be logged out.

## Step 10: Fix namespace issue in route for the Laravel 8.x version

At the moment of writing this steps, the version of Laravel is 8.x. There are lots of changes happened in this version. If you are using latest version of Laravel, you will see all models are now resides in `Models` directory. There are more significant changes happened. If you want to know more about Laravel changes you can follow this [release note](https://laravel.com/docs/8.x/releases#laravel-8).
Another issue is that in Laravel 8.x the namespace in `RouteServiceProvider` is null by default. So if you are trying to use AuthController in your api route it will not find the specified class because namespace is null by default. If you want to use it, you need to manually type the class name and prefix with the namespace.
So, better option is you can open `RouteServiceProvider` and create a new property called `namespace` and assign the value with `App\Http\Controller` and its done. So, whenever you try to access any controller you don't need to worry about this problem.

## Step 11: Create Task model, migration and resource controller

We can create a Task model, migration and a resourceful controller so that, logged in user can view, create, update and delete their tasks.

Laravel provides a beautiful command to create model, migrations and resourceful controllers using only one command, let's enter following command:

```bash
php artisan make:model Task -mcr
```

It will create three files, one is Task model inside the Models directory. Second one is a migration file inside the `/database/migrations/` directory. And third one is a resourceful controller inside the `/app/http/controllers` directory.
We are saying resourceful controller because the Task controller already has `index`, `create`, `store`, `edit`, `show`, `update`, `delete` methods and we can use them for the CRUD(Create-Read-Update-Delete) operation.

Now open the Migration file from the `/database/migrations` directory and specify title(string), details(string), created_by(unsignedBigInteger) to create three more columns and apply foreign key to the tasks table and reference it wit the users table on id column.

Now we need to make a relationship method so that the User model knows the relation with the Task model. So, open up the User model and add a new method called tasks and use `$this->hasMany(Task::class, 'created_by', 'id')`. These paremeters are pretty self explanatory. First one is the class to relate with the Task model, second parameter is the primary key of the related table and third parameter is the primary key of the current User model.

Now run the following migration command to create new tasks table into the database.

```bash
php artisan migrate
```

If you already migrated earlier you can use following command:

```bash
php artisan migrate:fresh
```

You can use any database GUI to see what changes happen in your database.

## Step 12: Modify UserFactory and create TaskFactory

Now we need to modify our UserFactory, you don't need to create the UserFactory. Because Laravel already provided this factory for default User model.
Open this factory file from the `database/factories` directory. And only change the password value to use `Hash::make('123456')` and don't forget to import this Hash facade.
So, remember the password is '123456', if you want to make it another you can change it tough.

Now we need to create a new factory. For that enter following command in your CLI.

```bash
php artisan make:factory TaskFactory
```

It will create a TaskFactory inside the `database/factories` directory.
So, open it up and add following inside it:

```php
<?php
public function definition()
{
    return [
        'title'      => $this->faker->sentence,
        'details'    => $this->faker->sentence,
        'created_by' => rand(1, 10),
    ];

}//end definition()
?>
```

Laravel gave a nice tool called tinker. It allows us to interact with a database without creating the routes. It is used to create the objects or modify the data.
So with following command we can open a PHP CLI Shell:

```bash
php artisan tinker
```

Now we need to apply our factory using tinker. So we can create 10 dummy users using following command in tinker:

```bash
User::factory(10)->create();
```

or

```bash
App\Models\User::factory()->count(10)->create();
```

Then we can create 50 dummy tasks using following commands:

```bash
Task::factory(50)->create();
```

Then we can type `exit` and hit enter to exit from the tinker shell.

## Step 13: Build CURD system with TaskController

Now we can create a `user` property in our TaskController then add a `__construct` method to assign the user property with JWT authenticate method:

```php
<?php
public function __construct() {
    $this->user = JWTAuth::parseToken()->authenticate();
}
?>
```

And don't forget to import JWTAuth.

In the index method we can get all the tasks of logged in users using tasks method we already created for relationship.

```php
<?php
public function index() {
    return $this->user->tasks()->get(['title', 'details', 'created_by'])->toArray();
}
?>
```

To store tasks into the database we can use store method:

```php
<?php
public function store(Request $request) {
    $this->validate($request, [
        'title' => 'required',
        'details' => 'required'
    ]);

    $task = new Task();
    $task->title = $request->title;
    $task->details = $request->details;

    if($this->user->tasks()->save($task)) {
        return response()->json([
            'status' => true,
            'task' => $task
        ]);
    }
    else {
        return response()->json([
            'status' => false,
            'message' => 'Oops, task could not be saved.'
        ]);
    }
}
?>
```
