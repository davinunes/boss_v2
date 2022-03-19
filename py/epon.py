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
		
child = pexpect.spawn ('/usr/bin/ssh -o StrictHostKeyChecking=no '+user+'@'+HOST) #option needs to be a list
child.setwinsize(10000,10000)
child.timeout = 150
child.logfile = sys.stdout #display progress on screen

child.expect('password:') #waiting for password
child.sendline (password) #sending password
time.sleep(2)
child.expect('OLT8PON>')
time.sleep(1)

#go up enable configuration
child.sendline ('enable\n\n\n')
time.sleep(2)
# child.expect('enable')
# child.sendline ('config') #going to ENABLE configuration
# child.expect('config')

#show ont register-statistics all


# child.sendline ('interface epon 0/0') #going to ENABLE configuration
# child.expect('#')