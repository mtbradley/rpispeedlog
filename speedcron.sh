#!/bin/sh
# Use this script for scheduled cron task
python3 /home/pi/rpispeedlog/speedtest.py
python3 /home/pi/rpispeedlog/speedplot.py
