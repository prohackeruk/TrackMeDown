#!/bin/bash
# Install
# This should be unnecessary soon
touch /checkroot 2>/dev/null

uid=`stat -c "%u" /checkroot 2>/dev/null`

if [ "$uid" = "0" ]
then
		echo "Removing old version..."
		rm -r /var/www/html/*
		echo "Copying new version..."
		cp -r ./* /var/www/html
		echo "Starting server..."
		# Just for my system
		systemctl start apache2 # Start apache, it doesn't matter if it's already running, it will just pass
		# systemctl start <your preferred web server>
		systemctl start mysql # Start MySQL
		# systemctl start <your preferred database>
		echo "Running!"
else
		echo "You need to be root"
fi
