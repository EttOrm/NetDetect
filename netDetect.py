import subprocess
import re
import time
import sqlite3
import os

connection = sqlite3.connect("/home/pi/NetDetect/db.db")

cursor = connection.cursor()

cursor.execute("""select * from mac""")

result = cursor.fetchall()

cursor.execute("""select mac from mac""")

sqlmac = cursor.fetchall()

cursor.execute("""select * from key""")

lightInf = cursor.fetchall()

print lightInf[0][1]

device = 0

user = "abcdefgh"

on = 'coap-client -m put -u "' + user + '" -k "' + lightInf[0][4] + '" -e' + "'" + '{ "3311": [{ "5850": 1 }] }' + "'" + ' "coaps://' + lightInf[0][1] + ':5684/' + lightInf[0][5] + '"'

off = 'coap-client -m put -u "' + user + '" -k "' + lightInf[0][4] + '" -e' + "'" + '{ "3311": [{ "5850": 0 }] }' + "'" + ' "coaps://' + lightInf[0][1] + ':5684/' + lightInf[0][5] + '"'

print on

while True:

    var = subprocess.check_output(['sudo' ,'arp-scan','-l', '-x', '-q'])

    for i in range(len(result)):

        mac = ''.join(sqlmac[i])

        

        print mac
       
        if mac in var:
                device += 1

    if device > 0:

        
        os.system(on)
        print "a"
    
    else:

        os.system(off)
        print "b"

    device = 0

    time.sleep(10)

