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
onu_mac = sys.argv[2]
slot = sys.argv[3]
pon = sys.argv[4]
onu_tipo = sys.argv[5]

child = pexpect.spawn ('telnet '+HOST) #option needs to be a list

time.sleep(1)

child.expect ('Login:')
child.sendline (USER)

child.expect('Password:')
child.sendline (PASSWORD)
time.sleep(1)

child.sendline ('enable') #going to ENABLE configuration
child.expect('Password:') #waiting enable password
child.sendline (PASSWORD) #sending enable password 

child.expect('#')



child.sendline ('cd onu')


child.sendline ('set whitelist phy_addr address '+onu_mac+' password null action add slot '+slot+' pon '+pon+' onu null type '+onu_tipo)
time.sleep(1)
child.sendline ('show onu-authinfo phy-id '+onu_mac)

child.logfile_read = sys.stdout

child.sendline ('cd .')
child.sendline ('cd .')
child.sendline ('cd .')
child.sendline ('cd .')
child.sendline ('exit')
child.expect('>')
child.sendline ('exit')
