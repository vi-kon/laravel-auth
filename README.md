# Laravel rola authenticator

This is **Laravel 4** package for role base authenticating.

## Known issues

* none

## TODO

* Fix incomming bugs

## Features
* 
* 

## Models

* Group
* Role
* User
* UserPasswordReminder

### Group

**Namespace**: `ViKon\Auth\models`

**Database table**: `users`

#### Read/Write Properties

| Type        | Name     | Description               | Default | Database                |
| ----------- | -------- | ------------------------- |:-------:| ----------------------- |
| integer     | `id`     | Unique group identifier   | -       | primary key, increments |
| string      | `name`   | Human readable group name | -       |                         |
| string      | `token`  | Unique group name (token) | null    | unique, nullable        |
| boolean     | `static` | Disallow deleting on GUI  | false   |                         |
| boolean     | `hidden` | Disallow showing on GUI   | false   |                         |

#### Read properties (relations)

| Type                      | Name   | Description      | Default | Database                                       |
| ------------------------- | ------ | ---------------- |:-------:| ---------------------------------------------- |
| \ViKon\Auth\models\User[] | `user` | Users collection | -       | many to many relation with `users` table       |
| \ViKon\Auth\models\Role[] | `role` | Roles collection | -       | many to many relation with `user_roles` table  |



### Role

### User

### UserPasswordReminder