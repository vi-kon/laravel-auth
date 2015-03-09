# Laravel 5 role based authentication

This is **Laravel 5** package for role based authenticating.

## Table of content

* [Todo](#todo)
* [Changes](#changes)
* [Features](#features)
* [Installation](#installation)
* [Models](#models)
* [Helper classes](#helper-classes)
* [Middleware](#middleware)
* [Smarty plugins](#smarty-plugins)

---
[Back to top][top]

## Todo

* Fix incoming bugs
* Finish documentation

---
[Back to top][top]

## Changes

Version 2.0

- **Laravel 5** support (requirement)
- Removed **AuthUser** and **AuthRoute** aliases
- Added **ViKon\Auth\AuthUser** and **ViKon\Auth\AuthRoute** singletons
- Package filters changed to **middleware** classes
- Removed **auth.home** filter (middleware)
- Code optimalization with Laravel 5 new features and conventions
- Service provider is now deferred

---
[Back to top][top]

## Features

* Role based access
* Grouping roles
* Filter routes by individual roles

---
[Back to top][top]

## Installation

### Basic

To your `composer.json` file add following lines:

```javascript
// to your "require" object
"vi-kon/laravel-auth": "2.*"
```
In your Laravel 5 project add following lines to `app.php`:
```php
// to your providers array
'ViKon\Auth\AuthServiceProvider',
```

---
[Back to top][top]

### Aliases

Optionally you can add aliases back to `app.php`:
```php
// to your aliases array
'AuthUser'  => 'ViKon\Auth\Facades\AuthUser',
'AuthRoute' => 'ViKon\Auth\Facades\AuthRoute',
```

---
[Back to top][top]

### Middleware

No need assign short-hand key to `Kernel`'s `routeMiddleware` properties, because ServiceProvider do it automatically.

---
[Back to top][top]

## Models

* [Group](#group-model)
* [Role](#role-model)
* [User](#user-model)
* [UserPasswordReminder](#userpasswordreminder-model)

Models are using pivot tables for many to many relations: `rel_role_group`, `rel_user_role`, `rel_user_group`.

---
[Back to top][top]

### Group model

Group is for managing user roles as collection.

**Namespace**: `ViKon\Auth\models`

**Database table**: `user_groups`

#### Read/Write Properties

| Type    | Name          | Description               | Default | Database                |
| ------- | ------------- | ------------------------- |:-------:| ----------------------- |
| integer | `id`          | Unique group identifier   | -       | primary key, increments |
| string  | `name`        | Human readable group name | -       | length 255              |
| string  | `description` | Short description         | -       | length 1000             |
| string  | `token`       | Unique group name (token) | null    | unique, nullable        |
| boolean | `static`      | Disallow deleting on GUI  | false   |                         |
| boolean | `hidden`      | Disallow showing on GUI   | false   |                         |

#### Read properties (relations)

| Type                      | Name    | Description      | Default | Database                                       |
| ------------------------- | ------- | ---------------- |:-------:| ---------------------------------------------- |
| \ViKon\Auth\Models\User[] | `users` | Users collection | -       | many to many relation with `users` table       |
| \ViKon\Auth\Models\Role[] | `roles` | Roles collection | -       | many to many relation with `user_roles` table  |

#### Methods (relations)

Relations for Laravel Query Builder.

| Type          | Name      | Description                      | Database                                       |
| ------------- | --------- | -------------------------------- | ---------------------------------------------- |
| BelongsToMany | `users()` | Users relation for query builder | many to many relation with `users` table       |
| BelongsToMany | `roles()` | Roles relation for query builder | many to many relation with `user_roles` table  |


---
[Back to top][top]

### Role model

Role is for allowing users to access routes or certain actions.

**Namespace**: `ViKon\Auth\models`

**Database table**: `user_roles`

#### Read/Write Properties

| Type    | Name          | Description            | Default | Database                |
| ------- | ------------- | ---------------------- |:-------:| ----------------------- |
| integer | `id`          | Unique role identifier | -       | primary key, increments |
| string  | `name`        | Unique role name       | -       | unique                  |
| string  | `description` | Short description      | -       | length 1000             |

#### Read properties (relations)

| Type                       | Name     | Description       | Default | Database                                        |
| -------------------------- | -------- | ----------------- |:-------:| ----------------------------------------------- |
| \ViKon\Auth\models\User[]  | `users`  | Users collection  | -       | many to many relation with `users` table        |
| \ViKon\Auth\models\Group[] | `groups` | Groups collection | -       | many to many relation with `user_groups` table  |

#### Methods (relations)

Relations for Laravel Query Builder.

| Type          | Name       | Description                       | Database                                       |
| ------------- | ---------- | --------------------------------- | ---------------------------------------------- |
| BelongsToMany | `users()`  | Users relation for query builder  | many to many relation with `users` table       |
| BelongsToMany | `groups()` | Groups relation for query builder | many to many relation with `user_group` table  |


---
[Back to top][top]

### User model

User representing model, implements `UserInterface`.

**Namespace**: `ViKon\Auth\models`

**Database table**: `users`

#### Read/Write Properties

| Type    | Name             | Description                      | Default | Database                |
| ------- | ---------------- | -------------------------------- |:-------:| ----------------------- |
| integer | `id`             | Unique user identifier           | -       | primary key, increments |
| string  | `username`       | Username                         | -       | unique                  |
| string  | `password`       | User password                    | -       | length 255              |
| string  | `email`          | User e-mail address              | -       | length 255              |
| string  | `remember_token` | Remember token for "Remember me" | null    | nullable                |
| string  | `home`           | User home route name             | null    | nullable                |
| boolean | `blocked`        | Check if user is blocked         | false   |                         |
| boolean | `static`         | Disallow deleting on GUI         | false   |                         |
| boolean | `hidden`         | Disallow showing on GUI          | false   |                         |

#### Read properties (relations)

| Type                          | Name        | Description          | Default | Database                                                    |
| ----------------------------- | ----------- | -------------------- |:-------:| ----------------------------------------------------------- |
| \ViKon\Auth\Models\Role[]     | `roles`     | Users collection     | -       | many to many relation with `user_roles` table           |
| \ViKon\Auth\Models\Group[   ] | `groups`    | Groups collection    | -       | many to many relation with `user_groups` table           |
| \ViKon\Auth\Models\Reminder[] | `reminders` | Reminders collection | -       | many to many relation with `user_password_reminders` table  |

#### Methods (relations)

Relations for Laravel Query Builder.

| Type          | Name          | Description                          | Database                                                    |
| ------------- | ------------- | ------------------------------------ | ----------------------------------------------------------- |
| BelongsToMany | `roles()`     | Users relation for query builder     | many to many relation with `user_roles` table               |
| BelongsToMany | `groups()`    | Groups relation for query builder    | many to many relation with `user_group` table               |
| BelongsToMany | `reminders()` | Reminders relation for query builder | many to many relation with `user_password_reminders` table  |


---
[Back to top][top]

### UserPasswordReminder model

Stores password reminder tokens with store time.

**Namespace**: `ViKon\Auth\models`

**Database table**: `user_password_reminders`

#### Read/Write Properties

| Type    | Name         | Description                      | Default | Database                |
| ------- | ------------ | -------------------------------- |:-------:| ----------------------- |
| integer | `id`         | Unique reminder identifier       | -       | primary key, increments |
| integer | `user_id`    | User id                          | -       | index                   |
| string  | `token`      | Password token                   | -       |                         |
| Carbon  | `created_at` | Created at time                  | -       |                         |

#### Read properties (relations)

| Type                    | Name   | Description | Default | Database                                     |
| ----------------------- | ------ | ----------- |:-------:| -------------------------------------------- |
| \ViKon\Auth\models\User | `user` | User model  | -       | many to one relation with `user_roles` table |

#### Methods (relations)

Relations for Laravel Query Builder.

| Type      | Name     | Description                     | Database                                      |
| --------- | -------- | ------------------------------- | --------------------------------------------- |
| BelongsTo | `user()` | User relation for query builder | many to many relation with `user_roles` table |


---
[Back to top][top]

## Helper classes

* [AuthUser class](#authuser-class)
	* [getUser](#authusergetuser)
	* [getUserId](#authusergetuserid)
	* [hasRole](#authuserhasrole)
	* [hasRoles](#authuserhasroles)
	* [isBlocked](#authuserisblocked)
* [AuthRoute class](#authroute-class)
	* [getRoles](#authroutegetroles)
	* [hasCurrentUserAccess](#authroutehascurrentuseraccess)
	* [isPublic](#authrouteispublic)

### AuthUser class

The `AuthUser` class allow to check if current user has role or multiple roles.

### Methods

* [getUser](#authusergetuser) - get current user
* [getUserId](#authusergetuserid) - get current user's id
* [hasRole](#authuserhasrole) - check if current user has specific role
* [hasRoles](#authuserhasroles) - check if current user has all roles
* [isBlocked](#authuserisblocked) - check if current user is blocked or not

---
[Back to top][top]

#### AuthUser::getUser

Get current user.

```php
mixed AuthUser::getUser()
```

Return `NULL` if user is not authenticated, otherwise instance of `\ViKon\Auth\models\User`.

---
[Back to top][top]

#### AuthUser::getUserId

Get current user's id.

```php
mixed AuthUser::getUserId()
```

Return `NULL` if user is not authenticated, otherwise user's id.

---
[Back to top][top]

#### AuthUser::hasRole

Check if current user has specific role.

```php
bool AuthUser::hasRole(string $role)
```
| Type                | Name     | Description                                |
| ------------------- | -------- | ------------------------------------------ |
| `string`            | `$role`  | name of specific role                      |

Return `boolean` value. `TRUE` if current user has specific role, `FALSE` otherwise.


---
[Back to top][top]

#### AuthUser::hasRoles

Check if current user has all roles passed as parameter.

```php
bool AuthUser::hasRoles(mixed $role1 [, string $role2 [, string $role3 [, ... ] ] ])
```

| Type                   | Name     | Description                                |
| ---------------------- | -------- | ------------------------------------------ |
| `string` or `string[]` | `$role1` | name of first role or array of all roles   |
| `string`               | `$role2` | name of second role                        |
| `string`               | `$role3` | name of third role                         |

If more then one parameter passed to method, then all parameters are used as single role and converted to string.


---
[Back to top][top]

#### AuthUser::isBlocked

Check if current user is blocked or not.

```php
bool AuthUser::isBlocked()
```

Return `TRUE` if user is authenticated and is blocked, otherwise `FALSE`.

---
[Back to top][top]

### AuthRoute class

The `AuthRoute` class allow to get authentication information from route.

### Methods

* [getRoles](#authroutegetroles) - get roles for a named route
* [hasCurrentUserAccess](#authroutehascurrentuseraccess) - check if current user has access to named route
* [isPublic](#authrouteispublic) - check if route is public (route has no roles)

TODO

---
[Back to top][top]

## Middleware

Auth middleware classes allow to filter individual routes by their custom roles.

* [HasAccess](#hasaccess-middleware) - check if current user have roles to current route

---
[Back to top][top]

### HasAccess  middleware

Check if user have all roles to current route. To add role(s) to route only need add `roles` key to route options with right roles.

**Note**: If current route's `roles` key is empty or not exists, then `HasAccess` do nothing.

#### Configuration

In **config.php** file has multiple options. The following options are avalaible:

```php
[
    'login'     => [
        'route'    => 'login',
    ],
    'error-403' => [
        'route' => 'error-403'
    ],
]
```

If user is not authenticated and route need role permission(s), then HasAccess redirect user to `login.route` config value. If user is authenticated and hasn't got enough permission to access route, then HasAccess redirect to `error-403.route` config value. Otherwise HasAccess allow access to route.

**Note:** The `login.route` and `error-403.route` store the route name.

On 403 error the following parameters are flashed to session during redirect:

* **route-request-uri** - with full URL
* **route-roles** - array with list of roles needed by route

#### Usage

```php
// check if user have "admin" role
$options = [
    'middleware' => 'auth.role',
    'roles'      => 'admin',
];
Route::get('URL', $options);

// check if user have "admin" and "superadmin" roles
$options = [
    'middleware' => 'auth.role',
    'roles'      => ['admin', 'superadmin'],
];
Route::get('URL', $options);
```

---
[Back to top][top]

## Smarty plugins

For using this plugins need [vi-kon/laravel-smarty-view](https://github.com/vi-kon/laravel-smarty-view) package. Installation instruction found on package documentation.

* [has-role](#has-role-plugin)

---
[Back to top][top]

### has-role plugin

The **has-role** tag is alias for:

```php
return \AuthUser::hasRole($roleName);
```

Return value is type of `boolean`. Can throw `\SmartyException` exception.

#### Attributes

| Type     | Name      | Description     | Required | Default |
| -------- | --------- | --------------- |:--------:| ------- |
| `string` | `role`    | Role token name | x        | -       |

#### Usage

```smarty
{if {has_role role="admin.index"}}
  ...
{/if}
```

---
[Back to top][top]

## License

This package is licensed under the MIT License

---
[Back to top][top]

[top]: #laravel-5-role-based-authentication