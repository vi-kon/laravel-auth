# Laravel role based authenticator

This is **Laravel 4** package for role base authenticating.

## Known issues

* none

## TODO

* Fix incoming bugs
* Finish documentation
* Auto copy migration file

## Features

* Role based access
* Grouping roles
* Filter routes by individual roles

## Installation

To your `composer.json` file add following lines:

```javascript
// to your "require" object
"vi-kon/laravel-auth": "1.*"
```
In your Laravel 4 project add following lines to `app.php`:
```php
// to your providers array
'ViKon\Auth\AuthServiceProvider',
```

## Models

* Group
* Role
* User
* UserPasswordReminder

Models are using pivot tables for many to many relations: `rel_role_group`, `rel_user_role`, `rel_user_group`.

### Group

Group is for managing user roles as collection.

**Namespace**: `ViKon\Auth\models`

**Database table**: `user_groups`

#### Read/Write Properties

| Type    | Name     | Description               | Default | Database                |
| ------- | -------- | ------------------------- |:-------:| ----------------------- |
| integer | `id`     | Unique group identifier   | -       | primary key, increments |
| string  | `name`   | Human readable group name | -       |                         |
| string  | `token`  | Unique group name (token) | null    | unique, nullable        |
| boolean | `static` | Disallow deleting on GUI  | false   |                         |
| boolean | `hidden` | Disallow showing on GUI   | false   |                         |

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


### Role

Role is for allowing users to access routes or certain actions.

**Namespace**: `ViKon\Auth\models`

**Database table**: `user_roles`

#### Read/Write Properties

| Type    | Name   | Description            | Default | Database                |
| ------- | ------ | ---------------------- |:-------:| ----------------------- |
| integer | `id`   | Unique role identifier | -       | primary key, increments |
| string  | `name` | Unique role name       | -       | unique                  |

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

### User

User representing model, implements `UserInterface`.

**Namespace**: `ViKon\Auth\models`

**Database table**: `users`

#### Read/Write Properties

| Type    | Name             | Description                      | Default | Database                |
| ------- | ---------------- | -------------------------------- |:-------:| ----------------------- |
| integer | `id`             | Unique user identifier           | -       | primary key, increments |
| string  | `username`       | Username                         | -       | unique                  |
| string  | `password`       | User password                    | -       |                         |
| string  | `email`          | User e-mail address              | -       |                         |
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

### UserPasswordReminder

Stores password reminder tokens with store time.

**Namespace**: `ViKon\Auth\models`

**Database table**: `user_password_reminders`

#### Read/Write Properties

| Type    | Name         | Description                      | Default | Database                |
| ------- | ------------ | -------------------------------- |:-------:| ----------------------- |
| integer | `id`         | Unique reminder identifier       | -       | primary key, increments |
| string  | `user_id`    | User id                          | -       |                         |
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

## AuthRole class

AuthRole class allow to check if current user has role or multiple roles.

### Methods

* hasRole
* hasRoles

TODO

## Auth filters

Auth filter allow to filter individual routes or redirect user to their home route.

* auth.role
* auth.home

### auth.role filter

Check if user have certain roles. To add role(s) to route only need add one of `role` or `roles` key to route options with right role.

#### Usage

```php
$options = array(
    'before' => 'auth.role',
    // check if user have admin role
    'role'   => 'admin',
);
Route::get('URL', $options);

$options = array(
    'before' => 'auth.role',
     // check if user have admin and superadmin roles
    'roles'  => array('admin','superadmin'),
);
Route::get('URL', $options);


```

### auth.home

Redirect user to "home" route if in User model home is not null and user is logged in.

#### Usage

```php
$options = array(
    'before' => 'auth.home',
);
Route::get('URL', $options);
```

## License

This package is licensed under the MIT License