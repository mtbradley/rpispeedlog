# Manual setup and configuration (rpispeedlog)

### Getting started  
Assumes you have a base configured Raspberry Pi with working Internet connection. [Basic Raspberry Pi setup information](https://github.com/mtbradley/rpispeedlog/blob/master/raspberrypi_initial_setup.md)

### Install required packages
In terminal update and install the following packages:

 ```
sudo apt-get install ntpdate -y
sudo apt-get install python3-pip -y
sudo apt-get install python3-matplotlib -y
sudo apt-get install python3-pandas -y
sudo apt-get install git -y
sudo apt-get install apache2 -y
sudo apt-get install php libapache2-mod-php -y
sudo apt-get install speedtest-cli -y
 ```

### Clone rpispeedlog
Working from the pi user home directory /home/pi

`git clone https://github.com/mtbradley/rpispeedlog.git`

### Edit Apache2 configuration files:

Use preferred editor ie vim or nano

`sudo vim /etc/apache2/apache2.conf` or 

`sudo nano /etc/apache2/apache2.conf`

In the apache2.conf file add the following lines:
```
<Directory /home/pi/rpispeedlog/>
        Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted
</Directory>

Alias "/speedlog" "/home/pi/rpispeedlog/www"
```

Restart Apache update configuration changes

`sudo /etc/init.d/apache2 restart`

This will enable access to web interface at:
http://yourpiipaddress/speedlog

### Demo data

If setup correctly you should be able to view the interface now. The database will contain sample data.

If your web inferface is working you can remove/delete the demo data as follows:

Delete SQLite3 database file

`rm /home/pi/rpispeedlog/speedresults.db`

Recreate blank SQLite3 database file

`touch /home/pi/rpispeedlog/speedresults.db`

Delete sample plot images

`rm /home/pi/rpispeedlog/www/plots/*`


### Running rpispeedlog manually
Use the shell script by running:

`/home/pi/rpispeedlog/speedcron.sh`

### Updating crontab to run at hourly intervals
As user Pi run command:

`crontab -e`

Add line to the crontab (one shown is for hourly updates)

`0 * * * * /home/pi/rpispeedlog/speedcron.sh`

Exit and save the crontab changes.

(As speedtest-cli uses external servers I would not recommend updating to frequently as this may lead to your IP being blocked or blacklisted)

