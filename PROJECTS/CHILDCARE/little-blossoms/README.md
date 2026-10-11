# Little Blossoms Childcare — PHP / MySQL

A complete demonstration childcare web application for a 3-person student group.

## Requirements
PHP 8.1+ with PDO MySQL, MySQL/MariaDB, Apache (XAMPP is fine), and a web browser. Bootstrap is loaded from a CDN (internet needed for Bootstrap styling).

## Run locally (XAMPP)
1. Unzip and copy the **little-blossoms** folder into `C:\xampp\htdocs\`.
2. Start Apache and MySQL in XAMPP.
3. Open `http://localhost/phpmyadmin`, select Import, and import `database/database.sql`.
4. Check the database settings in `includes/connect.php`. Defaults are localhost, database `little_blossoms`, user `root`, blank password; environment variables DB_HOST, DB_NAME, DB_USER, DB_PASS can override these.
5. Open `http://localhost/little-blossoms/setup_admin.php` and create an administrator.
6. **Immediately delete `setup_admin.php` after use.** Never deploy it to the public server.
7. Open `http://localhost/little-blossoms/index.php`.
8. Use Registration to create a parent account; log in as parent or admin.

## Access levels
- Public: home, services, registration, testimonials, contact.
- Parent: above + own child's daily details and testimonial submission.
- Admin: above + homepage features, fees, daily record editing, testimonial approval, contact inbox, and service CRUD.

## Test flow
Register parent → log in → view own child → log in as admin → create daily record → log in as parent → view the record. Add testimonial as parent → approve as admin → check public testimonial page. Send contact message → read in admin inbox. Edit homepage feature and fee → verify public pages change.

## Architecture and security
- Shared includes: `connect.php` (PDO), `functions.php` (session/CSRF/authorization), `header.php` (database-driven navigation), `footer.php`.
- MySQL prepared statements for all user-provided query values; escaping for HTML output; CSRF tokens for state-changing forms; password_hash/password_verify; session ID regeneration after login; admin/parent role guards; parent-child ownership checks.
- The `day` table has a unique constraint on `(child_id,day_date)` so updates never duplicate a child's daily record.
- Childcare fee amounts and opening hours are **fictional demonstration content**, not actual business pricing.
- Uploaded photos are not used: supplied original SVG illustrations live in `assets/img`, and MySQL stores only their paths.

## Important production / grading notes
- All paths assume the site is deployed at `/little-blossoms/`. On Knuth, change the base path in `includes/header.php`, `includes/footer.php`, and the redirect in `require_role()` in `includes/functions.php` to match `~student/ass03` or the final folder.
- Registration uses PHP `mail()` to attempt confirmation emails. **Delivery is not guaranteed** without working SMTP/sendmail configuration; configure an authenticated mail service before claiming email delivery works.
- Do not use this demonstration application to collect real children's personal or medical data without additional security/privacy assessment, consent, retention policy and appropriate hosting.
- Add rate limiting for login, HTTPS, secure email delivery, and a privacy policy before public production deployment.
- Run HTML validation (Nu HTML Checker) and CSS validation (W3C CSS Validator) on rendered pages; validation has not been independently executed here.
- The assignment submission additionally needs the required project PDF, a database dump, and a ZIP with the group members' numbers; prepare these separately.

## Suggested team split
Member 1: public pages + responsive CSS. Member 2: registration + authentication + parent pages. Member 3: database + admin CRUD + integration/testing. All three should understand the whole codebase.
