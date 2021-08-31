#! /usr/bin/python
import sys,pexpect
import getpass
import time
import arrow

HOST = '172.24.4.2'

#configure here all variables following you system 
#=======================================================================
user = 'root'
password = 'admin'


#=======================================================================
#defining the actual date to be add to the filename
data = arrow.now().format('DD-MM-YYYY_HH-mm')
comando = 'copy startup-config ftp://nexus:nexus@192.168.147.41/$(SWITCHNAME)_'+data+'_conf.cfg vrf default'

child = pexpect.spawn ('/usr/bin/ssh '+user+'@'+HOST) #option needs to be a list
child.timeout = 150
child.logfile = sys.stdout #display progress on screen

child.expect('password:') #waiting for password
child.sendline (password) #sending password
time.sleep(2)
child.expect('OLT8PON>')
time.sleep(1)

#go up enable configuration
child.sendline ('enable') #going to ENABLE configuration
child.interact()
child.expect('OLT8PON#')
child.sendline ('config') #going to ENABLE configuration
child.sendline ('interface epon 0/0') #going to ENABLE configuration
child.expect('#')


