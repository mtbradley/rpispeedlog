Raspberry Pi Internet Speed Test Logger uses results from speedtest-cli at scheduled cron intervals, stores in SQLITE3, plots graph images using Python Matplotlib and displays image and database logs on webpage using Apache and PHP.

# Raspberry Pi Raspbian
**Tested with Raspbian Buster**
**Raspberry Pi 3b+**  
Helpful Raspberry Pi Guides and Scripts  

### Initial system setup  
1. Download Raspbian desktop or lite
   - Visit (https://www.raspberrypi.org/downloads/raspbian/)

2. Write Raspbian image to SD card.
   - Recommend using **balenaEtcher** for this process (https://www.balena.io/etcher/)

3. (Optional prior to inserting SD card into pi device)
   Enable ssh and wireless network on first boot  
   *Handy if using Raspbian Lite or running headless install without monitor*
   - Enable SSH
     - Create an empty file in called `ssh` on SD card
   - Enable wireless
     - Create file called `wpa_supplicant.conf` on SD card  
       Edit wpa_supplicant.conf with your wifi details:
       ```
       country=<country code may be required in some cases>      
       network={
       ssid="your_ssid"
       scan_ssid=1
       psk="your_wifi_password"
       }
       ```
4. Insert SD card into your Raspberry Pi and boot
   If you are using a desktop environment open "Terminal".  
   Alternately connect using ssh `ssh pi@<your_pi_ipaddress>`    
   Default password is: `raspberry`    
   Run `sudo raspi-config`  
   Navigate the config tool and set the following:
   - Change User Password
   - Expand filesystem (if given option)
   - Localisation Options > Change Timezone (if required)
   - Network Options > Setup hostname and Wi-fi (if required)
 
 5. Static IP address assignment (optional)  
    Add static IP assignment to the end of `/etc/dhcpcd.conf` file.  
    Run `sudo nano /etc/dhcpcd.conf`  
    Example assigning static IP to onboard wireless NIC:  
    *If assigning IP for ethernet NIC change wlan0 to eth0*  
    ```
    # define static for wireless NIC WLAN0
    interface wlan0
    static ip_address=(your_pi_ip>) ie. 192.168.0.5
    static routers=(your_network_gateway) ie. 192.168.0.1 if this is your gateway router  
    static domain_name_servers=(your_preferred_DNS) ie. 192.168.0.1 or 8.8.8.8
    ```
      
 **The following commands are run through a terminal or the CLI of the pi**  
 *They require a working Internet connection*  
   
 6. Run initial update and upgrade the base system packages   
 ```
 sudo apt-get update -y
 sudo apt-get dist-upgrade -y
 ```
 7. Install basic packages
    - ntpdate (Helps pi sync and keep valid time with NTP server)
    - git (If not already with base install. For working with git and cloning repositories etc.)
 ```
 sudo apt-get install ntpdate -y
 sudo apt-get install git -y
 ```
