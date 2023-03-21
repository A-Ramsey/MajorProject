# 3rd Year Major Project
Major Project

After git clone to server add details to .env files and config files
- APP_URL
- Database details
- MAIL_FROM_ADDRESS
- MAILGUN_DOMAIN & MAILGUN SECRET

Emails, if set up with sandbox (as is on test site they only send to validated emails so contact Aaron (aar17@aber.ac.uk if you need to test them)

Then run ```php artisan migrate```

To create a new super admin run the command ```php artisan new-super-admin```
For the first option give it your name, second email (this must be unique and not used in the system before) and the final one set a password
It will then create a new Super Admin to log in with
This may need to be done after the site has been set up for the first time

Files worked on
- App/Enums/ (all within)
- App/Models/ (all within)
- App/Http/Controllers/ (all within except Controller.php)
- App/Http/Middleware/RoleCheck.php
- App/View/Components/list-empty-notice.php
- database/migrations/ (all from 2022_02_20_122211_create_events_table.php and newer)
- public/assets/app.css
- public/assets/app.js
- resources/views/ (all within)
- routes/web.php
- routes/console.php (the new-super-admin route)
