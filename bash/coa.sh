#!/bin/bash
PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin" 
# set -x 
#  $1 = user-name
#  $2 = Valor Atributo
echo "User-Name=\"$1\", Nas-Identifier=\"10.111.114.2\", ERX-Service-Deactivate:1=\"IPV4\", ERX-Service-Activate:1=\"$2\"" >/tmp/coa
cat /tmp/coa | /usr/bin/radclient -d /usr/share/freeradius/ -sx 10.111.114.2 coa esqueci