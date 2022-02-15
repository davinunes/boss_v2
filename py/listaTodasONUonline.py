#!/usr/bin/env python
#-------------------------------------
#by Jorge Luiz Taioque
#jorgeluiztaioque at gmail dot com
#www.networktips.com.br
#-------------------------------------
#backup OLTs and ONUs fiberhome
#Usage 
#./bk-olt-fiberhome.py IP_ADDRESS


import sys,pexpect
import getpass
import time
import sys


USER = 'GEPON'
PASSWORD = 'GEPON'
ENPASSWORD = 'GEPON'

HOST = sys.argv[1]

child = pexpect.spawn ('telnet '+HOST) #option needs to be a list
child.setwinsize(10000,10000)
time.sleep(2)

child.expect ('Login:')
child.sendline (USER)

child.expect('Password:')
child.sendline (PASSWORD)
time.sleep(1)

child.sendline ('enable') #going to ENABLE configuration
child.expect('Password:') #waiting enable password
child.sendline (PASSWORD) #sending enable password 
child.expect('#')
child.sendline ('cd service')
child.expect('#')
child.sendline ('terminal length 0')
child.expect('#')
child.sendline ('cd ..')
child.expect('#')
child.sendline ('cd onu')


child.sendline ('show authorization slot all pon all')
child.logfile_read = sys.stdout

child.sendline ('cd .')
child.sendline ('exit')
child.expect('>')
child.sendline ('exit')
