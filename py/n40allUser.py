#! /usr/bin/python
import sys,pexpect
import getpass
import time
# import arrow
HOST = "10.77.1.2"
#configure here all variables following you system 
#=======================================================================
user = 'suporte'
password = 'Senha@Super131377'

#=======================================================================
#defining the actual date to be add to the filename

child = pexpect.spawn ('/usr/bin/ssh -o StrictHostKeyChecking=no -p 1822 '+user+'@'+HOST) #option needs to be a list
child.setwinsize(10000,10000)
child.timeout = 150
child.logfile = sys.stdout #display progress on screen
child.expect('Enter password:') #waiting for password
child.sendline(password) #sending password
time.sleep(2)
child.expect('>')
child.sendline('display access-user normal item | no-more')
time.sleep(2)
time.sleep(2)
child.expect('>')
child.sendline('quit')


