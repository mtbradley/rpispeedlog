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

import os
import sys
import re
import subprocess
import time
import sqlite3
from sqlite3 import Error

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

def create_table(tableSql):
    try:
        if conn is not None:
            cur = conn.cursor()
            cur.execute(tableSql)
            conn.commit()
            print("Table OK.")
    except Error as e:
        print(e)

def insert_result(resultData):
    sql = ''' INSERT INTO results(date, time, ping, download, upload) VALUES(?,?,?,?,?) '''
    try:
        if conn is not None:
            cur = conn.cursor()
            cur.execute(sql, resultData)
            conn.commit()
            print("Result data inserted into results table.")
    except Error as e:
        print(e)

def select_all_results():
    try:
        if conn is not None:
            cur = conn.cursor()
            cur.execute("SELECT * FROM results")
            rows = cur.fetchall()
            for row in rows:
                print(row)
    except Error as e:
        print(e)

def get_speed_results():
    print("Fetching speedtest results...")
    rDate = time.strftime('%d/%m/%y')
    rTime = time.strftime('%H:%M')
    try:
        response = subprocess.Popen('/usr/bin/speedtest-cli --simple', shell=True, stdout=subprocess.PIPE).stdout.read()
        response = str(response)
    except subprocess.CalledProcessError as e:
        print(e)
        sys.exit()
    ping = re.findall('Ping:\s(.*?)\s', response, re.MULTILINE)
    download = re.findall('Download:\s(.*?)\s', response, re.MULTILINE)
    upload = re.findall('Upload:\s(.*?)\s', response, re.MULTILINE)
    ping = ping[0].replace(',', '.')
    download = download[0].replace(',', '.')
    upload = upload[0].replace(',', '.')
    results = (rDate, rTime, ping, download, upload)
    return results

resultData = get_speed_results()
database = "/home/pi/rpispeedlog/speedresults.db"
conn = create_connection(database)

resultsTable = """ CREATE TABLE IF NOT EXISTS results (
                                    id integer PRIMARY KEY,
                                    date text NOT NULL,
                                    time text NOT NULL,
                                    ping real NOT NULL,
                                    download real NOT NULL,
                                    upload real NOT NULL
                                ); """

create_table(resultsTable)
insert_result(resultData)
select_all_results()
close_connection()
