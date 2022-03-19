import os
import paramiko
from datetime import datetime

host2 = '172.24.4.2'
port2 = 22
username2 = 'root'
password2 = 'admin'

def connectSSH():
  ssh = paramiko.SSHClient()
  ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
  ssh.connect(host2, port2, username2, password2)

  return ssh

def disconnectSSH(ssh):
  ssh.close()

def runCommand(command):
  ssh = connectSSH()

  stdin, stdout, stderr = ssh.exec_command(command)
  lines = stdout.readlines()

  disconnectSSH(ssh)

  return lines

def excludeBreakLines(text):
  # get index of break line
  indexOfBreakLine = text.find('\r')
  return text[0: indexOfBreakLine]

def getPeers():
  peers = []

  # log to machine
  result = runCommand('enable \n configure \n ')