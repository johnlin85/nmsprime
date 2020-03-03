# Fill DHCPd config with dummy values

# create OMAPI key
echo "Creating OMAPI key"
OMAPI_KEYFILE_SUFF="_omapi_key"
OMAPI_KEYFILE="my"$OMAPI_KEYFILE_SUFF
cd /etc/dhcp-nmsprime
rm -f K*$OMAPI_KEYFILE_SUFF*
dnssec-keygen -r /dev/urandom -a HMAC-MD5 -b 512 -n HOST $OMAPI_KEYFILE
OMAPI_SECRET=$(cat K$OMAPI_KEYFILE.+*.private |grep ^Key|cut -d ' ' -f2-)
sed -i "s|<OMAPI_SECRET>|$OMAPI_SECRET|" /etc/dhcp-nmsprime/failover.conf
sed -i "s|<OMAPI_KEYNAME>|$OMAPI_KEYFILE|" /etc/dhcp-nmsprime/failover.conf

# make use of failover.conf (catch existing/not existing entry)
echo "Enabling failover.conf"
sed -i 's|#include "/etc/dhcp-nmsprime/failover.conf"|include "/etc/dhcp-nmsprime/failover.conf"|' /etc/dhcp-nmsprime/dhcpd.conf
grep -c "failover.conf" /etc/dhcp-nmsprime/dhcpd.conf || sed -i "s|deny bootp;|deny bootp;\n\n# The following config is used by module ProvHA to provide DHCP failover.\ninclude \"/etc/dhcp-nmsprime/failover.conf\";|" /etc/dhcp-nmsprime/dhcpd.conf

# make .env files readable for apache
chgrp -R apache /etc/nmsprime/env
chmod -R o-rwx /etc/nmsprime/env
chmod -R g-w /etc/nmsprime/env

chmod 600 /etc/dhcp-nmsprime/K*$OMAPI_KEYFILE_SUFF*
chown root.root /etc/dhcp-nmsprime/K*$OMAPI_KEYFILE_SUFF*
chmod 640 /etc/dhcp-nmsprime/failover.conf
chgrp dhcpd /etc/dhcp-nmsprime/failover.conf

echo "Done"
echo "ATTENTION! You need to configure ProvHA to get it work:"
echo "    - /etc/nmsprime/env/provha.env"
echo "    - /etc/dhcp-nmsprime/failover.conf"
echo "    - firewalld"
echo "    - global config in WebGUI"
echo

# Attention: Do NOT exit here – scripts seem to be concat; explicitely set exit causes exit of combined script :-(
# exit 0
