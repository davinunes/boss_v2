#!/usr/bin/python3

import threading
import paramiko
import time
import sys
import os

class ssh:
    shell = None
    client = None
    transport = None
    last = ""
   

    def __init__(self, address, username, password):
        print("Connecting to", str(address) + ".")
        self.client = paramiko.client.SSHClient()
        self.client.set_missing_host_key_policy(paramiko.client.AutoAddPolicy())
        self.client.connect(address, username=username, password=password, look_for_keys=False)
        self.transport = paramiko.Transport((address, 22))
        self.transport.connect(username=username, password=password)

        thread = threading.Thread(target=self.process)
        thread.daemon = True
        thread.start()

    def closeConnection(self):
        if(self.client != None):
            self.client.close()
            self.transport.close()

    def openShell(self):
        self.shell = self.client.invoke_shell()

    def sendShell(self, command):
        self.last = self.last + "\n" + command + "\n"
        if(self.shell):
            self.shell.send(command + "\n")
        else:
            print("Shell not opened.")

    def ShellSpace(self):
        self.last = self.last + "\n \n"
        if(self.shell):
            self.shell.send(" ")
        else:
            print("Shell not opened.")

    def ShellAnswerY(self):
        self.shell.send("Y")

    def CommandEnable(self, p):
        self.WaitForPrompt()
        self.sendShell("enable")
        self.WaitForPrompt()
        self.sendShell(p)
        self.WaitForPrompt()

    def WaitForPrompt(self):
        while True:
            if(self.last.endswith(" $")):
                return

            if(self.last.endswith(" >")):
                return

            if(self.last.endswith(" #")):
                return

            if(self.last.endswith("Password:")):
                return

            if(self.last.endswith("--More-- or (q)uit")):
                self.ShellSpace()

    def process(self):
        global connection
        while True:
            # Print data when available
            if self.shell != None and self.shell.recv_ready():
                alldata = self.shell.recv(1024)
                while self.shell.recv_ready():
                    alldata += self.shell.recv(1024)
                strdata = str(alldata, "utf8")
                strdata.replace('\r', '')
                self.last = self.last + strdata
                print(strdata, end = "")
                if(strdata.endswith("$ ")):
                    print("\n$ ", end = "")


sshUsername = "ilunne"
sshPassword = "iyukiinna"
sshServer = "10.169.1.18"
enPassword = "iyukiinna"


connection = ssh(sshServer, sshUsername, sshPassword)
connection.openShell()

connection.CommandEnable(enPassword)
connection.WaitForPrompt()
connection.sendShell("show interfaces status 0/11")
# connection.sendShell("reload")
time.sleep(0.1)
connection.ShellAnswerY()
time.sleep(1.0)
os._exit(0)