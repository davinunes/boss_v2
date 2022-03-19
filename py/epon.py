#! /usr/bin/python
import sys,pexpect,pxssh
import getpass
import time

s = pxssh.pxssh()

s.login ("172.24.4.2", "root", "admin")

s.sendline ('enable')