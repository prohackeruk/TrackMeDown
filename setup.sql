-- This file regenerates the database that the service needs to function.
-- Warning: It will delete your user data if you run it again! Be careful. Comment out the appropriate lines if you don't want this to happen.
-- Another warning: I developed this with MariaDB, a MySQL-like database engine. This may not work in other engines (I know some of this isn't valid TransactSQL, for example).
DROP DATABASE IF EXISTS TrackMeDown;
CREATE DATABASE TrackMeDown;
USE TrackMeDown;
-- users: for users to log in and view the tracker map
DROP TABLE IF EXISTS users;
CREATE TABLE users (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, -- PK
	email VARCHAR(100) NOT NULL, -- basically a username with an email regex (handled by php, can be changed)
	password VARCHAR(250) NOT NULL -- hashed & salted by the register code
) ENGINE = InnoDB;
-- devices: registered when you view the tracker page for the first time. Inserted into by tracker/.
DROP TABLE IF EXISTS devices;
CREATE TABLE devices (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, -- PK
	token VARCHAR(50) NOT NULL, -- We need a unique way for each client to identify themselves on the client side in order to be able to support multiple clients at once -- to do this, we just open a PHP session with anybody who opens the page. They then send their cookie back with each request and we use that to tell which user we should log the ping against. Note that this actually logs sessions, not individual devices, so if a target somehow drops their session (at time of writing my code never clears it, and I'm not The Big Man when it comes to PHP in general, so I don't know exactly when else this can happen -- the only thing I can think of is them opening the page in a different browser or on a different device, which is slightly odd behaviour), then they'll appear as a different target. However, hopefully they'll appear in a similar-ish location, maybe even with the same IP address and user agent (unless they did switch browsers/devices), so you might be able to tell if it's likely to be the same person using only your brain.
	user_agent VARCHAR(100) NOT NULL, -- This can help you identify what kind of prey you've caught
	ip_remote_addr VARCHAR(50) NOT NULL, -- One of these two
	ip_forwarded_for VARCHAR(50) NULL -- values could be the user's real IP
) ENGINE = InnoDB;
-- locations: where all of the user locations get stored. Inserted into by logme/.
DROP TABLE IF EXISTS locations;
CREATE TABLE locations (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, -- PK
	device_id INT UNSIGNED NOT NULL, -- The target that this ping relates to. Note: this field has to be INT UNSIGNED because the field it FK's to is INT UNSIGNED, otherwise it's a type mismatch and the this table won't get created
	latitude DOUBLE NOT NULL, -- Half a coordinate
	longitude DOUBLE NOT NULL, -- The other half of the coordinate
	address VARCHAR(100) NOT NULL, -- Their address, reverse geocoded from the coordinates
	time_located DATETIME NOT NULL, -- The time at which the location ping was received
	CONSTRAINT `fk_location_device` -- Delete all location data about a device when the device is deleted
		FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE
) ENGINE = InnoDB;
