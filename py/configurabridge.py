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
onu_num = sys.argv[2]
slot = sys.argv[3]
pon = sys.argv[4]
vlan = sys.argv[5]

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
time.sleep(1)
child.sendline ('cd lan')
time.sleep(1)

child.sendline ('set epon slot '+slot+' pon '+pon+' onu '+onu_num+' port 1 service number 1')
time.sleep(1)
child.logfile_read = sys.stdout
child.sendline ('set epon slot '+slot+' pon '+pon+' onu '+onu_num+' port 1 service 1 vlan_mode tag 0 33024 '+vlan)
time.sleep(1)
child.sendline ('apply onu '+slot+' '+pon+' '+onu_num+' vlan')
time.sleep(1)
child.sendline ('cd .')
child.sendline ('cd .')
child.sendline ('cd .')
child.sendline ('exit')
child.expect('>')
child.sendline ('exit')
