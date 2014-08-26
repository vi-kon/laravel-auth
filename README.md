# Laravel 4 role based authentication

This is **Laravel 4** package for role based authenticating.

## Table of content

* [Known issues](#known-issues)
* [TODO](#todo)
* [Features](#features)
* [Installation](#installation)
* [Models](#models)
    * [Group](#group-model)
    * [Role](#role-model)
    * [User](#user-model)
    * [UserPasswordReminder](#userpasswordreminder)
* [Helper classes](#helper-classes)
    * [AuthUser class](#authuser-class)
	    * [getUser](#authusergetuser)
		* [hasRole](#authuserhasrole)
		* [hasRoles](#authuserhasroles)
		* [isBlocked](#authuserisblocked)
    * [AuthRoute class](#authroute-class)
		* [getRoles](#authroutegetroles)
		* [hasCurrentUserAccess](#authroutehascurrentuseraccess)
		* [isPublic](#authrouteispublic)
* [Filters](#filters)
	* [auth.role](#authrole-filter)
	* [auth.home](#authhome-filter)
* [Smarty plguins](#smarty-plugins)
	* [has-role](#has-role-plugin)

## Known issues

* none

---
[Back to top](#laravel-4-role-based-authentication)

## TODO

* Fix incoming bugs
* Finish documentation
* Auto copy migration file

---
[Back to top](#laravel-4-role-based-authentication)

## Features

* Role based access
* Grouping roles
* Filter routes by individual roles

---
[Back to top](#laravel-4-role-based-authentication)

## Installation

To your `composer.json` file add following lines:

```javascript
// to your "require" object
"vi-kon/laravel-auth": "1.1.*"
```
In your Laravel 4 project add following lines to `app.php`:
```php
// to your providers array
'ViKon\Auth\AuthServiceProvider',
```

---
[Back to top](#laravel-4-role-based-authentication)

## Models

* [Group](#group-model)
* [Role](#role-model)
* [User](#user-model)
* [UserPasswordReminder](#userpasswordreminder-model)

Models are using pivot tables for many to many relations: `rel_role_group`, `rel_user_role`, `rel_user_group`.


---
[Back to top](#laravel-4-role-based-authentication)

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
| \ViKon\Auth\models\User[] | `users` | Users collection | -       | many to many relation with `users` table       |
| \ViKon\Auth\models\Role[] | `roles` | Roles collection | -       | many to many relation with `user_roles` table  |

#### Methods (relations)

Relations for Laravel Query Builder.

| Type          | Name      | Description                      | Database                                       |
| ------------- | --------- | -------------------------------- | ---------------------------------------------- |
| BelongsToMany | `users()` | Users relation for query builder | many to many relation with `users` table       |
| BelongsToMany | `roles()` | Roles relation for query builder | many to many relation with `user_roles` table  |


---
[Back to top](#laravel-4-role-based-authentication)

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
[Back to top](#laravel-4-role-based-authentication)

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
| \ViKon\Auth\models\Role[]     | `roles`     | Users collection     | -       | many to many relation with `user_roles` table           |
| \ViKon\Auth\models\Group[   ] | `groups`    | Groups collection    | -       | many to many relation with `user_groups` table           |
| \ViKon\Auth\models\Reminder[] | `reminders` | Reminders collection | -       | many to many relation with `user_password_reminders` table  |

#### Methods (relations)

Relations for Laravel Query Builder.

| Type          | Name          | Description                          | Database                                                    |
| ------------- | ------------- | ------------------------------------ | ----------------------------------------------------------- |
| BelongsToMany | `roles()`     | Users relation for query builder     | many to many relation with `user_roles` table               |
| BelongsToMany | `groups()`    | Groups relation for query builder    | many to many relation with `user_group` table               |
| BelongsToMany | `reminders()` | Reminders relation for query builder | many to many relation with `user_password_reminders` table  |


---
[Back to top](#laravel-4-role-based-authentication)

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
[Back to top](#laravel-4-role-based-authentication)

## Helper classes

### AuthUser class

The `AuthUser` class allow to check if current user has role or multiple roles.

### Methods

* [getUser](#authusergetuser) - get current user
* [hasRole](#authuserhasrole) - check if current user has specific role
* [hasRoles](#authuserhasroles) - check if current user has all roles
* [isBlocked](#authuserisblocked) - check if current user is blocked or not

---
[Back to top](#laravel-4-role-based-authentication)

#### AuthUser::getUser

Get current user.

```php
mixed AuthUser::getUser()
```

Return `NULL` if user is not logged in, otherwise instance of `\ViKon\Auth\models\User`.

---
[Back to top](#laravel-4-role-based-authentication)

#### AuthUser::hasRole

Check if current user has specific role.

```php
bool AuthUser::hasRole(string $role)
```
| Type                | Name     | Description                                |
| ------------------- | -------- | ------------------------------------------ |
| `string`             | `$role`  | name of spacific role                      |

Return `boolean` value. `TRUE` if current user has specific role, `FALSE` otherwise.


---
[Back to top](#laravel-4-role-based-authentication)

#### AuthUser::hasRoles

Check if current user has all roles passed as parameter.

```php
bool AuthUser::hasRoles(mixed $role1 [, string $role2 [, string $role3 [, ... ] ] ])
```

| Type                  | Name     | Description                                |
| --------------------- | -------- | ------------------------------------------ |
| `string` or `string[]`  | `$role1` | name of first role or array of all roles   |
| `string`               | `$role2` | name of second role                        |
| `string`               | `$role3` | name of third role                         |

If more then one parameter passad to method, then all parameters are used as single role and converted to string.


---
[Back to top](#laravel-4-role-based-authentication)

#### AuthUser::isBlocked

Check if current user is blocked or not.

```php
bool AuthUser::isBlocked()
```

Return `TRUE` if user is logged in and is blocked, otherwise `FALSE`.

---
[Back to top](#laravel-4-role-based-authentication)

### AuthRoute class

The `AuthRoute` class allow to get authentication information from route.

### Methods

* [getRoles](#authroutegetroles) - get roles for a named route
* [hasCurrentUserAccess](#authroutehascurrentuseraccess) - check if current user has access to named route
* [isPublic](#authrouteispublic) - check if route is public (route has no roles)

TODO

---
[Back to top](#laravel-4-role-based-authentication)

## Filters

Auth filter allow to filter individual routes or redirect user to their home route.

* [auth.role](#authrole-filter) - check if current user have certain roles
* [auth.home](#authhome-filter) - redirect user to "home" route

---
[Back to top](#laravel-4-role-based-authentication)

### auth.role filter

Check if user have certain roles. To add role(s) to route only need add one of `role` or `roles` key to route options with right role.

#### Usage

```php
$options = array(
    'before' => 'auth.role',
    // check if user have admin role
    'roles'  => 'admin',
);
Route::get('URL', $options);

$options = array(
    'before' => 'auth.role',
     // check if user have admin and superadmin roles
    'roles'  => array('admin','superadmin'),
);
Route::get('URL', $options);
```

---
[Back to top](#laravel-4-role-based-authentication)

### auth.home filter

Redirect user to named "home" route if in User model home is not null and user is logged in.

#### Usage

```php
$options = array(
    'before' => 'auth.home',
);
Route::get('URL', $options);
```

## Smarty plugins

For using this plugins need [vi-kon/laravel-smarty-view](https://github.com/vi-kon/laravel-smarty-view) package. Installation instruction found on package documentation.

* [has-role](#has-role-plugin)

---
[Back to top](#laravel-4-role-based-authentication)

### has-role plugin

The **has-role** tag is alias for:

```php
return \AuthUser::hasRole($roleName);
```

Return value is type of `boolean`. Can throw `\SmartyException` exception.

#### Attributes

| Type   | Name      | Description    | Required | Default |
| ------ | --------- | -------------- |:--------:| ------- |
| string | `role`    | Role token name | x        | -       |

#### Usage

```smarty
{if {has_role role="admin.index"}}
  ...
{/if}
```

---
[Back to top](#laravel-4-role-based-authentication)

## License

This package is licensed under the MIT License

---
[Back to top](#laravel-4-role-based-authentication)