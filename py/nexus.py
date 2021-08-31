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
import arrow

HOST = sys.argv[1]

#configure here all variables following you system 
#=======================================================================
user = 'ilunne'
password = 'yuk11nn4'
FTPSERVER = '192.168.147.41'
ftpuser = 'nexus'
ftppassword = 'nexus'

#=======================================================================
#defining the actual date to be add to the filename
data = arrow.now().format('DD-MM-YYYY_HH-mm')
comando = 'copy startup-config ftp://nexus:nexus@192.168.147.41/$(SWITCHNAME)_'+data+'_conf.cfg vrf default'

child = pexpect.spawn ('/usr/bin/ssh '+user+'@'+HOST) #option needs to be a list
child.timeout = 150
child.logfile = sys.stdout #display progress on screen

child.expect('Password:') #waiting for password
child.sendline (password) #sending password
child.expect('#')

time.sleep(3)

#go up enable configuration
child.sendline (comando) #going to ENABLE configuration
child.expect('Password:') #waiting enable password
child.sendline (ftppassword) #sending enable password 
time.sleep(3)
child.expect('#')
child.sendline ('exit')