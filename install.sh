#!/bin/bash
# Install 
touch /checkroot 2>/dev/null

uid=`stat -c "%u" /checkroot 2>/dev/null`

if [ "$uid" = "0" ]
then
		echo "Removing old version..."
		rm -r /var/www/html/* # Remove old versions
		echo "Copying new version..."
		cp -r ./* /var/www/html # Copy new version
		echo "Starting server..."
		systemctl start apache2 # Start apache, it doesn't matter if it's already running, it will just pass
		# systemctl start nginx
		# systemctl start <your preferred web server>
		echo "Running!"
else
		echo "You need to be root"
fi
