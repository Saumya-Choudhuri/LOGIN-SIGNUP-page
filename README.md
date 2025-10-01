# LOGIN-SIGNUP-page


1. One-time setup (only if the environment is brand new)
(If any package is already installed, apt will skip it.)
--------------------------------------------------------------
sudo apt update
sudo apt install mysql-server redis-server php8.3 php8.3-mysql php8.3-redis

2. Start required services (run each session)
----------------------------------------------
sudo service mysql start
sudo service redis-server start


3. Initialize / reset the database
--------------------------------------
sudo mysql -u root -p

When prompted, enter your MySQL root password (leave blank and press Enter if you never set one).


Inside the MySQL prompt:
--------------------------
CREATE DATABASE IF NOT EXISTS login_signup;
USE login_signup;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255),
  age INT,
  dob DATE,
  contact VARCHAR(20)
);

Type exit; to leave MySQL.

4. Run the PHP app (always use system PHP)
Keep this terminal open—it’s serving the site.
---------------------------------------------------------
/usr/bin/php -S 0.0.0.0:10000 -t .

5. Optional health checks
-------------------------------------
/usr/bin/php -m | grep -E 'mysqli|redis'
redis-cli ping

6. Use the app in a browser
------------------------------------
Register: http://localhost:10000/register.html
Login: http://localhost:10000/login.html
Profile: http://localhost:10000/Sprofile.html
Watch the PHP terminal for any runtime errors.

7. Optional clean-ups
------------------------------
redis-cli FLUSHALL                                  # Clear sessions
sudo mysql -u root -p -e "TRUNCATE TABLE login_signup.users;"   # Reset users table


That’s everything you need to bring the stack up from scratch. If you’d like a script to automate parts of this or instructions for deployment, just let me know!