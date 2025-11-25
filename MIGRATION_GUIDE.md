# CodeIgniter 4 Migration Guide

## Migration Summary

This project has been migrated from CodeIgniter 4.4.4 to CodeIgniter 4.5+ (latest version).

## Changes Made

### 1. Composer Dependencies
- **Updated**: `composer.json`
  - PHP requirement: `^7.4 || ^8.0` → `^8.1` (CI4 4.5+ requires PHP 8.1+)
  - CodeIgniter framework: `^4.0` → `^4.5`

### 2. Code Updates

#### Redirect Methods
- **Fixed**: Deprecated `redirect()` calls updated to `redirect()->to()`
  - `app/Controllers/Orders.php` (3 instances)
  - `app/Models/Manager.php` (1 instance)

#### Filter Updates
- **Fixed**: `app/Filters/BranchFilter.php`
  - Added missing `return` statement for AJAX response

#### Configuration Updates
- **Updated**: `app/Config/Database.php`
  - Database charset: `utf8` → `utf8mb4`
  - Database collation: `utf8_general_ci` → `utf8mb4_general_ci`
  - (Recommended for better Unicode support)

- **Updated**: `app/Config/Filters.php`
  - Debug toolbar now only enabled in non-production environments

## Next Steps

### 1. Update Dependencies
Run the following command to update to the latest CI4 version:

```bash
composer update codeigniter4/framework
```

### 2. Verify PHP Version
Ensure your server is running PHP 8.1 or higher:

```bash
php -v
```

### 3. Test Application
After updating dependencies, thoroughly test:
- User authentication and sessions
- Database operations
- API endpoints
- Form submissions
- AJAX requests

### 4. Update Database Collation (Optional but Recommended)
If you want to update existing database tables to use utf8mb4:

```sql
ALTER DATABASE your_database_name CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

For individual tables:
```sql
ALTER TABLE table_name CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

## Breaking Changes in CI4 4.5+

### PHP Version Requirement
- **Minimum PHP version**: 8.1 (was 7.4)
- **Recommended**: PHP 8.2 or 8.3

### Deprecated Features
- Some deprecated methods may have been removed
- Check CI4 changelog for specific deprecations

### Database
- utf8mb4 is now recommended over utf8 for better Unicode support
- No breaking changes in query builder methods

## Compatibility Notes

### Still Compatible
- ✅ Session handling (`session()->get()`, `session()->set()`)
- ✅ Request methods (`request()->getPost()`, `request()->isAJAX()`)
- ✅ Response methods (`response()->setJSON()`)
- ✅ Database query builder
- ✅ Filters and middleware
- ✅ Routes configuration

### Testing Checklist
- [ ] User login/logout
- [ ] Session management
- [ ] Database queries
- [ ] API endpoints
- [ ] Form submissions
- [ ] File uploads
- [ ] Email sending
- [ ] Payment processing (Opayo, Sage)
- [ ] Product search and filtering
- [ ] Order management
- [ ] Admin panel

## Rollback Instructions

If you need to rollback:

1. Revert `composer.json`:
   ```json
   {
       "require": {
           "php": "^7.4 || ^8.0",
           "codeigniter4/framework": "^4.0"
       }
   }
   ```

2. Run `composer update codeigniter4/framework`

3. Revert code changes using git:
   ```bash
   git checkout HEAD -- app/Controllers/Orders.php
   git checkout HEAD -- app/Models/Manager.php
   git checkout HEAD -- app/Filters/BranchFilter.php
   git checkout HEAD -- app/Config/Database.php
   git checkout HEAD -- app/Config/Filters.php
   ```

## Additional Recommendations

### Security Improvements (Not Migration-Related)
While migrating, consider addressing these security issues:

1. **Password Hashing**: Replace MD5 with `password_hash()` (Critical)
2. **CSRF Protection**: Enable CSRF protection in `app/Config/Filters.php`
3. **SQL Injection**: Replace string concatenation with parameterized queries
4. **Environment Variables**: Move database credentials to `.env` file

## Support

For issues or questions:
- CodeIgniter 4 Documentation: https://codeigniter.com/user_guide/
- CodeIgniter Forum: https://forum.codeigniter.com/
- GitHub Issues: https://github.com/codeigniter4/CodeIgniter4/issues

