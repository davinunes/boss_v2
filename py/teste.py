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
slot = sys.argv[2]
pon = sys.argv[3]
onu = sys.argv[4]

child = pexpect.spawn ('telnet '+HOST) #option needs to be a list

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



child.sendline ('cd onu')


child.sendline ('show onu_state slot '+slot+' pon '+pon+' onu '+onu)
child.expect('#')
child.logfile_read = sys.stdout
# show onu_time
child.sendline ('show optic_module slot '+slot+' pon '+pon+' onu '+onu)
child.expect('#')

child.sendline ('show feport_status slot '+slot+' pon '+pon+' onu '+onu)
child.expect('#')
# child.sendline ('cd lan')
# child.sendline ('show onufe_service slot '+slot+' pon '+pon+' onu '+onu)


child.sendline ('cd .')
child.sendline ('cd .')
child.sendline ('cd .')
child.sendline ('cd .')
child.sendline ('exit')
child.expect('>')
child.sendline ('exit')
