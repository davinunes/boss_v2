#! /usr/bin/python
import sys,pexpect
import getpass
import time
# import arrow
HOST = 'huawei.network.education'
NOME = 'n8k_netEdu.vrp'
#configure here all variables following you system 
# Salvar progresso do roteador no curso do Elisaneo
#=======================================================================
user = 'aluno14'
password = 'NetWorkEducation#2022'

#=======================================================================
#defining the actual date to be add to the filename

child = pexpect.spawn ('/usr/bin/ssh -o StrictHostKeyChecking=no -p 2200 '+user+'@'+HOST) #option needs to be a list
child.setwinsize(10000,10000)
child.timeout = 150
child.logfile = sys.stdout #display progress on screen
child.expect('Enter password:') #waiting for password
child.sendline(password) #sending password
time.sleep(2)
child.expect('>')
child.sendline('display current-configuration > '+NOME)
time.sleep(2)
child.sendline('ftp client-transfile put host-ip 143.208.72.60 username git sourcefile '+NOME)
child.expect('Enter password:')
child.sendline('git')
time.sleep(2)
child.expect('>')
child.sendline('quit')


