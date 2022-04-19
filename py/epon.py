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

child = pexpect.spawn ('/usr/bin/sshpass -p admin ssh '+user+'@'+HOST) #option needs to be a list
child.setwinsize(10000,10000)
child.timeout = 150
child.logfile = sys.stdout
time.sleep(2)
child.expect('OLT8PON>')
time.sleep(1)

#go up enable configuration
child.sendline ('enable') #going to ENABLE configuration
# child.interact()
child.expect('password:')
child.expect('enable')
child.sendline ('enable')
