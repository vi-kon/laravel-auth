# Laravel 5.1 role-permission based authentication

This is **Laravel 5** package for role-permission based authenticating.

## Table of content

* [Features](#features)
* [Todo](#todo)
* [Changes](#changes)
* [Installation](#installation)
* [Models](#models)
* [Helper classes](#helper-classes)
* [Middleware](#middleware)
* [Packages](#packages)
* [Smarty plugins](#smarty-plugins)

---
[Back to top][top]

## Features

* Permission based access
* Grouping permissions (Roles)
* Restrict routes access by individual permission
* Allow same username, separated to individual namespaces (each namespace require
  individual login screen or option to specify namespace at login)

---
[Back to top][top]

## Todo

* Fix incoming bugs
* Finish documentation
* Write tests
* Cache metadata

---
[Back to top][top]

## Changes

Version 3.0

- **Laravel 5.1** support
- New `PermissionMiddleware` 
- Config publish location changed from `config/auth-role.php` to `config/vi-kon/auth.php`
- `Group` model renamed to `Role` and `Role` model renamed to `Permission` for better usability
- Models moved from `ViKon\Auth\Models` namespace to `ViKon\Auth\Model` namespace
- Removed Smarty support

Version 2.0.2

- Config option to add custom Profile model

Version 2.0.1

- Group usernames into separated "packages"

Version 2.0

- **Laravel 5** support (requirement)
- Removed **AuthUser** and **RouterAuth** aliases
- Added **ViKon\Auth\AuthUser** and **ViKon\Auth\RouterAuth** singletons
- Package filters changed to **middleware** classes
- Removed **auth.home** filter (middleware)
- Code optimization with Laravel 5 new features and conventions
- Service provider is now deferred

---
[Back to top][top]

## Installation

#### Composer

There are multiple way to install package via composer

* To your `composer.json` file add following lines:

    ```json
    // to your "require" object
    "vi-kon/laravel-auth": "~3.*"
    ```

* Or run following command in project root:

    ```bash
    composer require vi-kon/laravel-auth
    ```

    This command will add above line in your composer.json file and download
    required package files.

#### Setup

In your Laravel 5.1 project add following lines to `config/app.php`:

```php
// to providers array
\ViKon\Auth\AuthServiceProvider::class,
```

---
[Back to top][top]

### Aliases

Optionally you can add aliases back to `app.php`:
```php
// to aliases array
'RouterAuth' => \ViKon\Auth\Facades\RouterAuth::class,
```

---
[Back to top][top]

### Middleware

No need to assign short-hand key to `App\Http\Kernel`'s `routeMiddleware` property,
because `AuthServiceProvider` do it automatically. These middlewares are:

* [HasAccessMiddleware](#has-access-middleware)
* [PermissionMiddleware](#permission-middleware)

#### Has Access middleware

Has access middleware can attach to groups or routes. This middleware check if route
has assigned permission and if current user has permission to access that route.

##### Usage

Part of `routes.php`:

```php
// Single route
Route::get('/', [
    'middleware' => 'auth.has-access',
    'permission' => 'access.some.controller',
    'uses'       => 'SomeController@index',

]);
// Multiple routes in group
Route::group(['middleware' => 'auth.has-access'], function () {
    Route::get('/', [
        'permission' => 'access.some.controller',
        'uses'       => 'SomeController@index',
    ]);
});
```

#### Permission middleware

Permission middleware get permission from their argument.

---
[Back to top][top]

## Models

All models have configurable database names. These database names are hold under
`table.*` config value. In this short introduction default table names are used.
The package holds following models:

* [User](#user-model)
* [Role](#role-model)
* [Permission](#permission-model)
* [UserPasswordReminder](#userpasswordreminder-model)
* [Profile](#profile-model)

Models are using pivot tables for many to many relations: `rel__role__group`,
`rel__user__role`, `rel__user__group`.

> **Note**: All table names, even pivot table names are configurable via config
> file.

> **Warning**: Not recommended to change table names if database is already
> migrated, because migrations down method will try to execute on wrong tables.

---
[Back to top][top]

### User model

User representing model, implements `AuthenticatableContract` and
`CanResetPasswordContract` interfaces and use `Authenticatable` and
`CanResetPassword` traits.

**Namespace**: `ViKon\Auth\Model`

**Database table**: `users`

#### Read/Write Properties

| Type    | Name             | Description                      | Default  | Database                |
| ------- | ---------------- | -------------------------------- |:--------:| ----------------------- |
| integer | `id`             | Unique user identifier           | -        | primary key, increments |
| string  | `username`       | Username                         | -        |                         |
| string  | `password`       | User password                    | -        | length 255              |
| string  | `email`          | User e-mail address              | -        | length 255              |
| string  | `remember_token` | Remember token for "Remember me" | null     | nullable                |
| string  | `package`        | Package name                     | "system" | length 255              |
| string  | `home`           | User home route name             | null     | nullable                |
| boolean | `blocked`        | Check if user is blocked         | false    |                         |
| boolean | `static`         | Disallow deleting on GUI         | false    |                         |
| boolean | `hidden`         | Disallow showing on GUI          | false    |                         |

The `username` and `package` columns has contracted unique index.

#### Read properties (relations)

| Type                           | Name          | Description            | Default | Database                                                   |
| ------------------------------ | ------------- | ---------------------- |:-------:| ---------------------------------------------------------- |
| \ViKon\Auth\Model\Role[]       | `roles`       | Roles collection       | -       | many to many relation with `user_roles` table              |
| \ViKon\Auth\Model\Permission[] | `permissions` | Permissions collection | -       | many to many relation with `user_permissions` table        |
| \ViKon\Auth\Model\Reminder[]   | `reminders`   | Reminders collection   | -       | many to many relation with `user_password_reminders` table |

#### Methods (relations)

Relations for Laravel Query Builder.

| Type          | Name            | Description                            | Database                                                                                         |
| ------------- | --------------- | -------------------------------------- | ------------------------------------------------------------------------------------------------ |
| BelongsToMany | `roles()`       | Users relation for query builder       | many to many relation with `user_roles` table                                                    |
| BelongsToMany | `permissions()` | Permissions relation for query builder | many to many relation with `user_permissions` table                                              |
| BelongsToMany | `reminders()`   | Reminders relation for query builder   | many to many relation with `user_password_reminders` table                                       |
| HasOne        | `profile()`     | Return attached user profile           | one to one relation to `user_profile` table (this table can customized via custom Profile model) |

---
[Back to top][top]

### Role model

Role is for managing user permissions as collection. So this is a helper to add
multiple permissions to user. Each role can contain multiple permissions.

**Namespace**: `ViKon\Auth\Model`

**Database table**: `user_roles`

#### Read/Write Properties

| Type    | Name          | Description              | Default | Database                |
| ------- | ------------- | ------------------------ |:-------:| ----------------------- |
| integer | `id`          | Unique role identifier   | -       | primary key, increments |
| string  | `token`       | Unique role name (token) | null    | unique, nullable        |
| boolean | `static`      | Disallow deleting on GUI | false   |                         |
| boolean | `hidden`      | Disallow showing on GUI  | false   |                         |

#### Read properties (relations)

| Type                           | Name          | Description            | Default | Database                                            |
| ------------------------------ | ------------- | ---------------------- |:-------:| --------------------------------------------------- |
| \ViKon\Auth\Model\User[]       | `users`       | Users collection       | -       | many to many relation with `users` table            |
| \ViKon\Auth\Model\Permission[] | `permissions` | Permissions collection | -       | many to many relation with `user_permissions` table |

#### Methods (relations)

Relations for Laravel Query Builder.

| Type          | Name            | Description                      | Database                                            |
| ------------- | --------------- | -------------------------------- | --------------------------------------------------- |
| BelongsToMany | `users()`       | Users relation for query builder | many to many relation with `users` table            |
| BelongsToMany | `permissions()` | Roles relation for query builder | many to many relation with `user_permissions` table |


---
[Back to top][top]

### Permission model

Permissions is for granting some kind of access to users, like access routes or
certain actions. Each action should have individual permissions.

For example in REST controller should have `{action name}.index`,
`{action name}.show` `{action name}.create`, `{action name}.edit` and
`{action name}.destroy` permissions for individual actions.

**Namespace**: `ViKon\Auth\Model`

**Database table**: `user_permissions`

#### Read/Write Properties

| Type    | Name    | Description                   | Default | Database                |
| ------- | ------- | ----------------------------- |:-------:| ----------------------- |
| integer | `id`    | Unique permissions identifier | -       | primary key, increments |
| string  | `token` | Unique permissions token      | -       | unique                  |

#### Read properties (relations)

| Type                     | Name    | Description      | Default | Database                                      |
| ------------------------ | ------- | ---------------- |:-------:| --------------------------------------------- |
| \ViKon\Auth\Model\User[] | `users` | Users collection | -       | many to many relation with `users` table      |
| \ViKon\Auth\Model\Role[] | `roles` | Roles collection | -       | many to many relation with `user_roles` table |

#### Methods (relations)

Relations for Laravel Query Builder.

| Type          | Name      | Description                      | Database                                      |
| ------------- | --------- | -------------------------------- | --------------------------------------------- |
| BelongsToMany | `users()` | Users relation for query builder | many to many relation with `users` table      |
| BelongsToMany | `roles()` | Roles relation for query builder | many to many relation with `user_roles` table |

---
[Back to top][top]

### UserPasswordReminder model

Stores password reminder tokens with store time.

**Namespace**: `ViKon\Auth\Model`

**Database table**: `user_password_reminders`

#### Read/Write Properties

| Type    | Name         | Description                      | Default | Database                |
| ------- | ------------ | -------------------------------- |:-------:| ----------------------- |
| integer | `id`         | Unique reminder identifier       | -       | primary key, increments |
| integer | `user_id`    | User id                          | -       | index                   |
| string  | `token`      | Password token                   | -       |                         |
| Carbon  | `created_at` | Created at time                  | -       |                         |

#### Read properties (relations)

| Type                   | Name   | Description | Default | Database                                |
| ---------------------- | ------ | ----------- |:-------:| --------------------------------------- |
| \ViKon\Auth\Model\User | `user` | User model  | -       | many to one relation with `users` table |

#### Methods (relations)

Relations for Laravel Query Builder.

| Type      | Name     | Description                     | Database                                 |
| --------- | -------- | ------------------------------- | ---------------------------------------- |
| BelongsTo | `user()` | User relation for query builder | many to many relation with `users` table |


---
[Back to top][top]

### Profile model

Profile model is not implemented in this package. This is only for support to add
custom data to users via single one to one relation. In config with `profile`
option can customize Profile model location.

Profile model has only one restriction. Need to have `user_id` column, which is
point to User's model `id` column.

Short example for profile migration file:

```php
class CreateProfileTable extends Migration
{
    public function up()
    {
        Schema::create('user_profile', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');

            // Foreign connection to user table
            $table->unsignedInteger('user_id')
                  ->unique();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('user_profile');
    }
}
```

---
[Back to top][top]

## Helper classes

* [Guard class](#guard-class)
* [RouterAuth class](#routerauth-class)

### Guard class

The `Guard` class extends Laravel's default `Guard` class to grant access for new
features. These features allow to get permission for current user, check if user
is blocked or not.

### Methods

Some methods are not newly implemented, just overwritten.

* [attempt](#guardAttempt) - try to authenticate user with credentials (auto inject
  into credentials default role package if set)
* [hasPermission](#guardHasPermission) - check if authenticated user has a specific
  permission
* [hasPermissions](#guardHasPermissions) - check if authenticated user has multiple
  permissions at once
* [isBlocked](#guardIsBlocked) - check if authenticated user is blocked or not
* [user](#guardUser) - get authenticated user

TODO method descriptions

---
[Back to top][top]

### RouterAuth class

The `RouterAuth` class allow to get authentication information from route.

### Methods

* [hasAccess](#routerAuthHasAccess) - get roles for a named route
* [isPublic](#routerAuthIsPublic) - check if current user has access to named route
* [getPermissions](#routerAuthGetPermissions) - check if route is public (route has
  no permissions)

TODO method descriptions

---
[Back to top][top]

## Middleware

Middleware classes allow to filter individual routes by their attached permissions.

* [HasAccessMiddleware](#hasaccess-middleware) - check if authenticated user have
  single or multiple permissions to current route
* [HasAccessMiddleware](#hasaccess-middleware) - check if authenticated user have
  single permission to current route

> **Note:** Syntax is only difference between these middlewares.

---
[Back to top][top]

### PermissionMiddleware middleware

> **Note:** The router cannot get parameter from this middleware. 

### HasAccessMiddleware middleware

Check if authenticated user have all permissions to current route. To add
permission(s) to route only need add `permissions` key to route options with right
permissions.

```php
Router::get('/', [
    'permissions' => 'permission.name',
]);
```

Or if multiple permissions are passed (Authenticated user have to has all
permissions to access route):

```php
Router::get('/', [
    'permissions' => [
        'permission.first.name',
        'permission.second.name',
    ],
]);
```

> **Note**: If current route's `permissions` key is empty or not exists, then route
> is mark as public and no route restriction will made.

#### Configuration

With `php artisan publish --provider="ViKon/Auth/AuthServiceProvider" --tag="config"`
command you can publish all config files to `vi-kon/auth.php`.

In this file there are multiple options. The following options are available:

* **login.route** - login screen route name
* **error-403.route** - route name if user is already authenticated but has no
  access to route

If user is not authenticated and route is not public, then middlewares redirect
user to named route, where named route name is stored in `login.route`
config value.

If user is authenticated and hasn't got enough permissions to access route, then
middlewares redirect to named route stored in `error-403.route` config
value.

Otherwise middlewares allow access to route.

> **Note:** The `login.route` and `error-403.route` stores the route name.

On 403 error the following parameters are flashed to session during redirect:

* **route-request-uri** - with full URL
* **route-roles** - array with list of roles needed by route

#### Usage

```php
// check if user have "admin" role
$options = [
    'middleware'  => 'auth.role',
    'permissions' => 'admin',
];
Route::get('URL', $options);

// check if user have "admin" and "superadmin" roles
$options = [
    'middleware'  => 'auth.role',
    'permissions' => ['admin', 'superadmin'],
];
Route::get('URL', $options);
```

---
[Back to top][top]

## Packages

Packages are useful for grouping users into individual packages. In each package usernames are unique, however in other package using the same username is permitted.

The default package is `system`. All users are stored in this package. 

### Authentication

For authenticating user in default package is simple, just call `attempt` method as usual:

```php
if (Auth::attempt(['email' => $email, 'password' => $password]))
{
    return redirect()->intended('dashboard');
}
```

For authenticating in custom package, need to provide package name:

```php
if (Auth::attempt(['email' => $email, 'password' => $password, 'package' => $package]))
{
    return redirect()->intended('dashboard');
}
```

---
[Back to top][top]

## License

This package is licensed under the MIT License

---
[Back to top][top]

[top]: #laravel-5-role-based-authentication