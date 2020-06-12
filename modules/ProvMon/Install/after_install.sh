# source environment variables to use php 7.1
source scl_source enable rh-php71

# variables
env='/etc/nmsprime/env'
source "$env/root.env"
mysql_cacti_psw=$(pwgen 12 1) # SQL password for user nmsprime_cacti
admin_psw='admin'

# set cacti psw in env file
sed -i "s/^CACTI_DB_PASSWORD=$/CACTI_DB_PASSWORD=$mysql_cacti_psw/" "$env/provmon.env"

# create DB accessed by cactiuser
# allow cacti to access time_zone_name table
mysql -u "$ROOT_DB_USERNAME" --password="$ROOT_DB_PASSWORD" << EOF
CREATE DATABASE cacti CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci';
GRANT ALL ON cacti.* TO 'cactiuser'@'localhost' IDENTIFIED BY '$mysql_cacti_psw';
GRANT SELECT ON mysql.time_zone_name TO 'cactiuser'@'localhost';
EOF

# set psw in cacti db config file
sed -i "s/^\$database_password =.*/\$database_password = '$mysql_cacti_psw';/" /etc/cacti/db.php

# populate default DB
# NOTE: for some unknown reasons, doing this in one line, like "mysql ... < ", does not work. maybe due two special char * in file link
cacti_file=`ls /usr/share/doc/cacti-*/cacti.sql`
mysql cacti -u cactiuser --password="$mysql_cacti_psw" < "$cacti_file"

# allow guest user to access graphs without login (also invalidate its password, by setting an imposible bcrypt hash)
# disable SNMP agent
mysql cacti -u cactiuser --password="$mysql_cacti_psw" << EOF
REPLACE INTO settings VALUES ('guest_user','guest');
REPLACE INTO settings VALUES ('enable_snmp_agent','');
UPDATE user_auth SET password='$(php -r "echo password_hash('$admin_psw', PASSWORD_DEFAULT);")', must_change_password='' WHERE username='admin';
UPDATE user_auth SET password='invalidated', must_change_password='', enabled='on' WHERE username='guest';
EOF

# link git files to the correct location, this way they are automatically updated
ln -srf /var/www/nmsprime/modules/ProvMon/Console/cacti/ss_docsis.php /usr/share/cacti/scripts/ss_docsis.php
ln -srf /var/www/nmsprime/modules/ProvMon/Console/cacti/cisco_cmts.xml /usr/share/cacti/resource/snmp_queries/cisco_cmts.xml

sed -i 's/Require host localhost$/Require all granted\n\t\tDirectoryIndex index.php/' /etc/httpd/conf.d/cacti.conf
systemctl reload httpd.service

# add tree categories, to group devices of same type, import cablemodem template from git
cd /usr/share/cacti/cli
su -s /bin/bash -c "php add_tree.php --type=tree --name='Cablemodem' --sort-method=natural" apache
su -s /bin/bash -c "php add_tree.php --type=tree --name='CMTS' --sort-method=natural" apache

# import all cacti host templates
for template in /var/www/nmsprime/modules/ProvMon/Console/cacti/cacti_host_template_*.xml; do
	su -s /bin/bash -c "php import_template.php --filename=$template" apache
done
#su -s /bin/bash -c "php install_cacti.php --install --accept-eula" apache

# add our css rules to cacti, if they haven't been added yet (see after_upgrade.sh as well)
file='/usr/share/cacti/include/themes/modern/main.css'
if [[ -e "$file" && -z $(grep -o nmsprime "$file") ]]; then
cat << EOF >> "$file"

/* nmsprime */

html {
	overflow: unset !important;
	overflow-x:hidden !important;
	overflow-y: visible !important;
	height: auto !important;
}

body:not(.loginBody) {
	overflow: unset !important;
	overlow-y: visible !important;
}

table {
	margin: 0 !important;
}

#cactiContent, #navigation_right {
	height: auto !important;
}
EOF
fi
