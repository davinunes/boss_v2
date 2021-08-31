#!/bin/bash

#Ajusta a PATH
PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin"

if [ -z $1 ]; then
	echo "Uso: $0 [tag da quadra] [(ip da impressora)]"
	exit 0
fi

set -x

# Salva informação da quadra
echo $1 > /root/portal/estaquadra


# Remove os dados de repositório e proxy do CTA caso existam no SO Padrão
mv /etc/apt/sources.list.d/7cta.list /root
mv /etc/apt/apt.conf.d/98aptconf7cta /root

# Ajusta o DNS
#echo "nameserver 10.133.44.53" > /etc/resolv.conf
echo "nameserver 8.8.8.8" >> /etc/resolv.conf

# Cria o alias na variavel para definir o padrão do apt-get
apt="apt-get -o Acquire::http::proxy=false::ForceIPv4=true"

# Define o nome das interfaces ppptp
intacesso=ppp1091
intpmb=ppp6164

# Instala SSH
$apt -y install openssh-server sshpass pptp-linux

# Configura para permitir acesso root via ssh
sed -i 's+#PermitRootLogin prohibit-password+PermitRootLogin yes+g' /etc/ssh/sshd_config
sed -i 's+#PermitTunnel no+PermitTunnel yes+g' /etc/ssh/sshd_config

#reinicia o servidor ssh
/etc/init.d/ssh restart


# Configura o l2tp para a acesso
echo 'pty "pptp 143.208.74.13 --nolaunchpppd"
debug
nodetach
logfd 2
noproxyarp
ipparam YOUR_COMPANY
remotename YOUR_COMPANY
#name vpn
require-mppe-128
nobsdcomp
nodeflate
lock
noauth
unit 1091
' > /etc/ppp/peers/acesso
echo "name $1" >> /etc/ppp/peers/acesso

# Configura o l2tp para a pmb
echo 'pty "pptp 10.133.44.1 --nolaunchpppd"
debug
nodetach
logfd 2
noproxyarp
ipparam YOUR_COMPANY
remotename YOUR_COMPANY
#name vpn
#require-mppe-128
nobsdcomp
nodeflate
lock
noauth
refuse-eap
refuse-chap
refuse-mschap
unit 6164
' > /etc/ppp/peers/pmb
echo "name $1" >> /etc/ppp/peers/pmb

# Cria o usuário, que é o mesmo em ambas
echo "$1 * $1 *" > /etc/ppp/chap-secrets


# Cria o script para a discagem para a acesso

# Cria a pasta portal
if [ ! -d "/root/portal" ]; then
        mkdir /root/portal
fi

# Cria a conexão para a acesso

shacesso="/root/portal/pppacesso.sh"

echo " " > $shacesso
# echo '#!/bin/bash' >> $shacesso
echo 'PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin"' >> $shacesso
echo "interface=$intacesso" >> $shacesso
echo 'ifconfig $interface 2>&1>/dev/null' >> $shacesso
echo 'RC=$?' >> $shacesso
echo 'if [ $RC == 1 ]; then' >> $shacesso
echo 'echo 1 > /proc/sys/net/ipv4/ip_forward' >> $shacesso
echo "iptables -t nat -A POSTROUTING -j MASQUERADE" >> $shacesso
echo "route -n | grep -E '^0.0.0.0 ' | cut -c 17-32 >/var/tmp/defaultgw" >> $shacesso
echo "route del -host 143.208.74.13" >> $shacesso
echo "poff acesso" >> $shacesso
echo "poff acesso" >> $shacesso
echo "/usr/bin/pon acesso updetach persist &" >> $shacesso
echo "sleep 15" >> $shacesso
echo 'route add default gw $(cat /var/tmp/defaultgw)' >> $shacesso
echo 'route add -net 10.133.44.0 netmask 255.255.252.0 metric 50 dev $interface' >> $shacesso
echo 'route add -net 10.133.88.0 netmask 255.255.252.0 metric 50 dev $interface' >> $shacesso
# echo 'echo "nameserver 10.133.44.53" > /etc/resolv.conf' >> $shacesso
echo 'echo "nameserver 8.8.8.8" >> /etc/resolv.conf' >> $shacesso
echo "fi" >> $shacesso

# Adiciona no Agendador de tarefas
cat <(crontab -l) <(echo "*/1 * * * * bash $shacesso") | crontab -

# Cria a conexão para a PMB

shpmb="/root/portal/ppppmb.sh"

echo ' ' > $shpmb
# echo '#!/bin/bash' >> $shpmb
echo 'PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin"' >> $shpmb
echo "interface=$intpmb" >> $shpmb
echo 'ifconfig $interface 2>&1>/dev/null' >> $shpmb
echo 'RC=$?' >> $shpmb
echo 'if [ $RC == 1 ]; then' >> $shpmb
echo "echo 1 > /proc/sys/net/ipv4/ip_forward" >> $shpmb
echo "iptables -t nat -A POSTROUTING -j MASQUERADE" >> $shpmb
echo "route -n | grep -E '^0.0.0.0 ' | cut -c 17-32 >/var/tmp/defaultgw" >> $shpmb
echo "route del -host 10.133.44.1" >> $shpmb
echo "poff pmb" >> $shpmb
echo "poff pmb" >> $shpmb
echo "/usr/bin/pon pmb updetach persist &" >> $shpmb
echo "sleep 15" >> $shpmb
echo 'route add default gw $(cat /var/tmp/defaultgw)' >> $shpmb
echo 'route add -net 10.133.44.0 netmask 255.255.252.0 metric 90 dev $interface' >> $shpmb
echo 'route add -net 10.133.88.0 netmask 255.255.252.0 metric 90 dev $interface' >> $shpmb
# echo 'echo "nameserver 10.133.44.53" > /etc/resolv.conf' >> $shpmb
echo 'echo "nameserver 8.8.8.8" >> /etc/resolv.conf' >> $shpmb
echo "fi" >> $shpmb

# Adiciona no Agendador de tarefas
cat <(crontab -l) <(echo "*/3 * * * * bash $shpmb") | crontab -
# /etc/init.d/cron restart

#Cria o Contador da impressora
shcontador="/root/portal/contador.sh"

echo 'MyToken="462987165:AAHpWmFvgUUlDAE7kSsSRBs2IR4pCPwy3as"
chatID="-1001421304194"
Message="$1:  "
Message+=$2
curl "https://api.telegram.org/bot$MyToken/sendMessage?chat_id=$chatID&text=$Message"' > /root/portal/telegram.sh

echo ' ' > $shcontador
echo '#!/bin/bash' >> $shcontador
echo 'contador=$(snmpwalk -v 1 -c public $1 1.3.6.1.2.1.43.10.2.1.4.1.1 | cut -d: -f2)' >> $shcontador
echo 'if [ $contador == "" ]; then
	exit 0
	fi
' >> $shcontador
echo 'bash /root/portal/telegram.sh "Contadores da $(cat /root/portal/estaquadra)" $contador' >> $shcontador

if [ $2 ]; then
	impressora=$2
	# Adiciona no Agendador de tarefas
	cat <(crontab -l) <(echo "0 13 20-25 * * bash $shcontador $impressora") | crontab -
	# /etc/init.d/cron restart
fi

cat <(crontab -l | sort | uniq -u) | crontab -
/etc/init.d/cron restart