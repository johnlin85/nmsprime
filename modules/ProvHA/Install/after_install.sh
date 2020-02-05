# Fill DHCPd config with dummy values

MASTER_ADDRESS='127.0.0.1'
MASTER_PORT=647
SLAVE_ADDRESS='192.0.2.100'	# “impossible” IP (https://tools.ietf.org/html/rfc5737)
SLAVE_PORT=647
OMAPI_PORT=7911
MCLT=1800

# init failover.conf with placeholder data 
sed -i "s|<PRIMARY_SECONDARY>|primary|" /etc/dhcp-nmsprime/failover.conf
sed -i "s|<OWN_ADDRESS>|$MASTER_ADDRESS|" /etc/dhcp-nmsprime/failover.conf
sed -i "s|<PEER_ADDRESS>|$SLAVE_ADDRESS|" /etc/dhcp-nmsprime/failover.conf
sed -i "s|<OWN_PORT>|$MASTER_PORT|" /etc/dhcp-nmsprime/failover.conf
sed -i "s|<PEER_PORT>|$SLAVE_PORT|" /etc/dhcp-nmsprime/failover.conf
sed -i '/load balance/ a \\tmclt '$MCLT';' /etc/dhcp-nmsprime/failover.conf
sed -i '/mclt/ a \\tsplit 128;' /etc/dhcp-nmsprime/failover.conf

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

# make .env files readable for apache
chgrp -R apache /etc/nmsprime/env
chmod -R o-rwx /etc/nmsprime/env
chmod -R g-w /etc/nmsprime/env

chmod 600 /etc/dhcp-nmsprime/K*$OMAPI_KEYFILE_SUFF*
chown root.root /etc/dhcp-nmsprime/K*$OMAPI_KEYFILE_SUFF*
chmod 640 /etc/dhcp-nmsprime/failover.conf
chgrp dhcpd /etc/dhcp-nmsprime/failover.conf

exit 0
