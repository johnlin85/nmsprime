#/bin/sh
echo "Configuring failover…"

MASTER_PORT=647
SLAVE_PORT=647
OMAPI_PORT=7911
MCLT=1800

# first ask the user if this is a master or a slave host
while true; do
	read -n1 -p "Is this a master [m] or a slave [s]? " INPUT
	case $INPUT in
		m|M)
			HOSTSTATE="master"
			break
			;;
		s|S)
			HOSTSTATE="slave"
			break
			;;
		*)
			echo
			echo "Invalid input"
			;;
	esac
	echo
done
echo
echo

# get the master and slave host from user
while true; do
	read -p "Enter MASTER IP or hostname to be used in DHCPd failover: " MASTER_ADDRESS
	read -p "Enter SLAVE IP or hostname to be used in DHCPd failover: " SLAVE_ADDRESS
	echo
	echo "master is $MASTER_ADDRESS"
	echo "slave  is $SLAVE_ADDRESS"
	read -n1 -p "Is this correct [y|n]? " INPUT
	case $INPUT in
		y|Y)
			break
			;;
		*)
			;;
	esac
done
echo
echo

# FOR DEVELOPING #
# HOSTSTATE="master"
# MASTER_ADDRESS="192.168.0.111"
# SLAVE_ADDRESS="192.168.0.116"
# cp -f /var/www/nmsprime/modules/ProvHA/Install/files/failover.conf /etc/dhcp-nmsprime
# cp -f /var/www/nmsprime/modules/ProvHA/Install/files/provha.env /etc/nmsprime/env
# FOR DEVELOPING #

echo "Configuring failover $HOSTSTATE"

case $HOSTSTATE in
	master)
		sed -i "s|<PROVHA__OWN_STATE>|master|" /etc/nmsprime/env/provha.env
		sed -i "s|<PRIMARY_SECONDARY>|primary|" /etc/dhcp-nmsprime/failover.conf
		sed -i "s|<OWN_ADDRESS>|$MASTER_ADDRESS|" /etc/dhcp-nmsprime/failover.conf
		sed -i "s|<PEER_ADDRESS>|$SLAVE_ADDRESS|" /etc/dhcp-nmsprime/failover.conf
		sed -i "s|<OWN_PORT>|$MASTER_PORT|" /etc/dhcp-nmsprime/failover.conf
		sed -i "s|<PEER_PORT>|$SLAVE_PORT|" /etc/dhcp-nmsprime/failover.conf
		sed -i '/load balance/ a \\tmclt '$MCLT';' /etc/dhcp-nmsprime/failover.conf
		sed -i '/mclt/ a \\tsplit 128;' /etc/dhcp-nmsprime/failover.conf
		;;
	slave)
		sed -i "s|<PROVHA__OWN_STATE>|slave|" /etc/nmsprime/env/provha.env
		sed -i "s|<PRIMARY_SECONDARY>|secondary|" /etc/dhcp-nmsprime/failover.conf
		sed -i "s|<OWN_ADDRESS>|$SLAVE_ADDRESS|" /etc/dhcp-nmsprime/failover.conf
		sed -i "s|<PEER_ADDRESS>|$MASTER_ADDRESS|" /etc/dhcp-nmsprime/failover.conf
		sed -i "s|<OWN_PORT>|$SLAVE_PORT|" /etc/dhcp-nmsprime/failover.conf
		sed -i "s|<PEER_PORT>|$MASTER_PORT|" /etc/dhcp-nmsprime/failover.conf
		;;
esac

# create OMAPI key
echo "Creating OMAPI key"
OMAPI_KEYFILE_SUFF="_omapi_key"
OMAPI_KEYFILE=$HOSTSTATE$OMAPI_KEYFILE_SUFF
cd /etc/dhcp-nmsprime
rm -f K*$OMAPI_KEYFILE_SUFF*
dnssec-keygen -r /dev/urandom -a HMAC-MD5 -b 512 -n HOST $OMAPI_KEYFILE
OMAPI_SECRET=$(cat K$OMAPI_KEYFILE.+*.private |grep ^Key|cut -d ' ' -f2-)
sed -i "s|<OMAPI_SECRET>|$OMAPI_SECRET|" /etc/dhcp-nmsprime/failover.conf
sed -i "s|<OMAPI_KEYNAME>|$OMAPI_KEYFILE|" /etc/dhcp-nmsprime/failover.conf
sed -i "s|<OMAPI_PORT>|$OMAPI_PORT|" /etc/dhcp-nmsprime/failover.conf

# make use of failover.conf
sed -i 's|#include "/etc/dhcp-nmsprime/failover.conf"|include "/etc/dhcp-nmsprime/failover.conf"|' /etc/dhcp-nmsprime/dhcpd.conf

# add zone for failover and populate it
echo
echo "Adding firewalld zone “failover”"
firewall-cmd --permanent --new-zone=failover
firewall-cmd --permanent --zone=failover --set-short="Failover"
firewall-cmd --permanent --zone=failover --set-description="Zone to provide access for failover peers"
firewall-cmd --permanent --zone=failover --add-port=$OMAPI_PORT/tcp
case $HOSTSTATE in
	master)
		firewall-cmd --permanent --zone=failover --add-port=$MASTER_PORT/tcp
		firewall-cmd --permanent --zone=failover --add-source=$SLAVE_ADDRESS
		;;
	slave)
		firewall-cmd --permanent --zone=failover --add-port=$SLAVE_PORT/tcp
		firewall-cmd --permanent --zone=failover --add-source=$MASTER_ADDRESS
		;;
esac
firewall-cmd --reload

# make .env files readable for apache
chgrp -R apache /etc/nmsprime/env
chmod -R o-rwx /etc/nmsprime/env
chmod -R g-w /etc/nmsprime/env

chmod 600 /etc/dhcp-nmsprime/K*$OMAPI_KEYFILE_SUFF*
chown root.root /etc/dhcp-nmsprime/K*$OMAPI_KEYFILE_SUFF*
chmod 640 /etc/dhcp-nmsprime/failover.conf
chgrp dhcpd /etc/dhcp-nmsprime/failover.conf

exit 0
