#! /usr/bin/python
import sys,pexpect
import getpass
import time
# import arrow

HOST = '172.24.4.2'

#configure here all variables following you system 
#=======================================================================
user = 'root'
password = 'admin'


#=======================================================================
#defining the actual date to be add to the filename
# data = arrow.now().format('DD-MM-YYYY_HH-mm')

child = pexpect.spawn ('/bin/bash -c "/usr/bin/ssh -o StrictHostKeyChecking=no '+user+'@'+HOST+'"') #option needs to be a list
child.setwinsize(10000,10000)
child.maxread=1000
child.timeout = 5
child.logfile = sys.stdout #display progress on screen

child.expect('password:') #waiting for password
child.sendline (password) #sending password
time.sleep(1)
child.expect('OLT8PON>')
# print(child.before)
time.sleep(1)
#go up enable configuration
child.sendline ('logout') #going to ENABLE configuration
child.expect('OLT8PON#')
# print(child.before)
time.sleep(1)
child.write ('config \n')
time.sleep(1)

time.sleep(1)




