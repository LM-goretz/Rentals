# Greenwood Rental House Management - Complete Package

This package contains a full starter system with the following:
- `db.sql` - MySQL schema and sample data (includes Daniel / 1234 etc. as immediate logins using SHA256 fallback)
- PHP backend (config.php, signin.php, signup.php, addhouse.php with image upload, dobooking.php, gethouses.php, addrating.php, admin.php)
- `convert_hashes.php` - guidance script (cannot reverse SHA256, see instructions)
- `uploads/` - directory to store uploaded images (ensure writable)
- Frontend pages in `public/` (index, signin, signup, addhouse, dobooking)

## Quick install
1. Copy files to your webroot (e.g. `/var/www/html/greenwood/`). Keep `public/` in the same folder.
2. Ensure `uploads/` is writable by the webserver (e.g. `chown www-data:www-data uploads`).
3. Import the database:
   ```
   mysql -u root -p < db.sql
   ```
4. Edit `config.php` and set `$db_user` and `$db_pass`.
5. Visit `http://your-server/greenwood/public/index.html`.

## Sample accounts (immediate)
- Daniel (tenant) — email: `daniel@example.com` password: `1234`
- Alice Owner (owner) — email: `owner@example.com` password: `Owner1234`
- Admin (admin) — email: `admin@example.com` password: `Admin1234`

> These sample accounts are stored using a SHA-256 prefix (`$sha256$`) so they work immediately. This is less secure than bcrypt.
> After installation, **replace** these hashes with bcrypt via:
> ```
> php -r "echo password_hash('1234', PASSWORD_DEFAULT).PHP_EOL;"
> ```
> Then update DB:
> ```
> UPDATE users SET password_hash = '<bcrypt-hash>' WHERE email = 'daniel@example.com';
> ```

## Admin dashboard
- Visit `http://your-server/greenwood/admin.php` and sign in as the admin user (Admin User / Admin1234) to manage bookings, houses, and users.

## Security notes
- Use HTTPS in production.
- Do not leave DB root or empty passwords.
- Use a restricted DB user for the app.
- Validate and limit file upload sizes and types (the code has basic checks but you should harden it).
- Consider rate-limiting login attempts and adding email verification.

