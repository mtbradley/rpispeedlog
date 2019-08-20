# rpispeedlog
RPi Speedlog can be used to track and log Internet download and upload speed performance on a Raspberry Pi. This project was an experiment to learn the basics of Matplotlib and other Python modules. [Setup and configuration](https://github.com/mtbradley/rpispeedlog/blob/master/SETUP.md) 

What it does: 
- Fetch Internet connection speed results using speedtest-cli at a scheduled cron interval.
- Store results using SQLITE3
- Plot Internet speed and generate plot/graph images using Python Matplotlib. 
- Display the plots/graph image along with database logs on webpage using Apache and PHP with selectable date.

### Screenshot of web interface
![screenshot web interface](https://raw.githubusercontent.com/mtbradley/rpispeedlog/master/screenshot.png)
