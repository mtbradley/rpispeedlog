#
# Copyright (c) 2019 Mark Bradley
# 
# This file is part of rpispeedlog
# https://github.com/mtbradley/rpispeedlog
# 
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or 
# any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#

import matplotlib.pyplot as plt
import pandas as pd
import datetime
import time
import sqlite3
from sqlite3 import Error

print("Commencing speedtest plot...")
today = datetime.datetime.today().strftime('%A')
rDate = time.strftime('%d/%m/%y')

def create_connection(db_file):
    try:
        conn = sqlite3.connect(db_file)
        print("Database connection is established.")
        return conn
    except Error as e:
        print(e)

def close_connection():
    try:
        if conn is not None:
            conn.close()
            print("Database connection is closed.")
    except Error as e:
        print(e)

print("Fetching result data for {0}...".format(rDate))
database = "/home/pi/rpispeedlog/speedresults.db"
conn = create_connection(database)
query = f"""
    SELECT * FROM results WHERE date = "{rDate}"
"""
data = pd.read_sql_query(query, conn)
print(data)

idate = data['date']
itime = data['time']
iping = data['ping']
idownload = data['download']
iupload = data['upload']
close_connection()

plt.rcParams.update({'font.family': 'sans-serif', 'legend.frameon': True, 'legend.framealpha': 0.5})

fig, (ax1, ax2, ax3) = plt.subplots(nrows=3, ncols=1, sharex=True)

ax1.plot(itime, idownload, linestyle='-', marker='', color='#458cd4', linewidth=1, label ='Download Mbps')
ax1.grid(True, alpha=0.25)
ax1.spines['right'].set_visible(False)
ax1.spines['top'].set_visible(False)
ax1.spines['left'].set_visible(False)
ax1.spines['bottom'].set_visible(False)
ax1.tick_params(axis=u'both', which=u'both',length=0, labelsize=8)
ax1.set_ylabel('Download Mbps', fontsize=6, alpha=0.5)
ax1.set_title("Internet Speedtest Results (" + today + " " + idate.iloc[0] + ")", fontsize=6, alpha=0.5)
ax1.fill_between(itime, idownload, alpha=0.25, color='#458cd4')
ax1.axhline(idownload.mean(axis=0), color='#458cd4', linestyle='--', linewidth=1)
ax1.set_xlim([0,23])

ax2.plot(itime, iupload, linestyle='-', marker='', color='#ffc191', linewidth=1, label ="Upload Mbps")
ax2.grid(True, alpha=0.25)
ax2.spines['right'].set_visible(False)
ax2.spines['top'].set_visible(False)
ax2.spines['left'].set_visible(False)
ax2.spines['bottom'].set_visible(False)
ax2.tick_params(axis=u'both', which=u'both',length=0, labelsize=8)
ax2.fill_between(itime, iupload, alpha=0.25, color='#ffc191')
ax2.set_ylabel('Upload Mbps', fontsize=6, alpha=0.5)
ax2.axhline(iupload.mean(axis=0), color='#ffc191', linestyle='--', linewidth=1)
ax2.set_xlim([0,23])

ax3.plot(itime, iping, linestyle='-', marker='', color='#c1d79c', linewidth=1, label ='Ping ms')
ax3.grid(True, alpha=0.25)
ax3.spines['right'].set_visible(False)
ax3.spines['top'].set_visible(False)
ax3.spines['left'].set_visible(False)
ax3.spines['bottom'].set_visible(False)
ax3.tick_params(axis=u'both', which=u'both',length=0, labelsize=8)
ax3.fill_between(itime, iping, alpha=0.25, color='#c1d79c')
ax3.set_ylabel('Ping ms', fontsize=6, alpha=0.5)
ax3.axhline(iping.mean(axis=0), color='#c1d79c', linestyle='--', linewidth=1)
ax3.set_xlim([0,23])

plt.xticks(rotation=60, fontsize=6)
plt.tight_layout()
imageName = ('{}.png'.format(rDate).replace('/', ''))
print("Plot image created: {0}".format(imageName))
plt.savefig('/home/pi/rpispeedlog/www/plots/{0}'.format(imageName), dpi=150)
