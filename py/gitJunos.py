#!/usr/bin/python
# /usr/bin/python /var/www/ilunne/boss/py/gitJunos.py > JunosBras.cfg && \
# curl -u ftp:asterix -T JunosBras.cfg ftp://143.208.72.6 && \
# rm -rf JunosBras.cfg
 
from paramiko import SSHClient
import paramiko

import sys
COMANDO = "show configuration | display set | no-more"
 
class SSH:
    def __init__(self):
        self.ssh = SSHClient()
        self.ssh.load_system_host_keys()
        self.ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        self.ssh.connect(hostname='10.111.114.2',port='22',username='ilunne',password='yuk11nn4')
 
    def exec_cmd(self,cmd):
        stdin,stdout,stderr = self.ssh.exec_command(cmd)
        if stderr.channel.recv_exit_status() != 0:
            print stderr.read()
        else:
            print stdout.read()
 
if __name__ == '__main__':
    ssh = SSH()
    ssh.exec_cmd(COMANDO)