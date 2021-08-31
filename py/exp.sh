#!/usr/bin/expect

set ip "172.24.4.2"

spawn "/bin/bash"
send "telnet $ip\r"
expect "'^]'."
send "\r"
expect ">"
sleep 2

send "root\n"
expect ">"

sleep 2
send -- "admin\r"
expect "#"
send  "show board\r"
expect eof