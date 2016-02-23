**Note**: This file is up to date only on master branch.

# Version 3.0

- **Laravel 5.1** support
- New `PermissionMiddleware` and `LoginRedirectorMiddleware` 
- Publish location for config changed from `config/auth-role.php` to `config/vi-kon/auth.php`
- `Group` model renamed to `Role` and `Role` model renamed to `Permission` for better usability
- Models moved from `ViKon\Auth\Models` namespace to `ViKon\Auth\Model` namespace
- Removed Smarty support
- User "packages" renamed to namespace

# Version 2.0.2

- Config option to add custom Profile model

# Version 2.0.1

- Group usernames into separated "packages"

# Version 2.0

- **Laravel 5** support (requirement)
- Removed **AuthUser** and **RouterAuth** aliases
- Added **ViKon\Auth\AuthUser** and **ViKon\Auth\RouterAuth** singletons
- Package filters changed to **middleware** classes
- Removed **auth.home** filter (middleware)
- Code optimization with Laravel 5 new features and conventions
- Service provider is now deferred