prohack-id is a lightweight PHP-based identity service designed to be plugged into web apps in order to save myself (and possibly you!) the time involved.

DATABASE DETAILS: HIGHLY IMPORTANT, PLEASE READ
-----------------------------------------------
This ID service requires a database to function.
If you look in database.php, you can change the connection details to hook up to your database.
Fortunately, there's a setup.sql file in the database/ directory -- running this will create a database called prohackid with the requisite table. From that point you can feel free to expand your database however you'd like.

The registration code prevents you from signing up with the same email twice so you can use it as a foreign key in other tables. Any changes you make to the code that break duplicate email checking are your responsibility, so be careful!
