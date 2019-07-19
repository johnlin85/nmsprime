<?php

class PopulateTftpdCmLog extends BaseMigration
{
    protected $tablename = '';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        echo "Populating /var/log/nmsprime/tftpd-cm.log, this may take some minutes. You can track the progress by following the file.\n";

        // only necessary for git installations
        if (! is_dir('/var/log/nmsprime')) {
            mkdir('/var/log/nmsprime', 0755);
            chown('/var/log/nmsprime', 'apache');
            chgrp('/var/log/nmsprime', 'apache');
        }

        shell_exec('for file in /var/log/messages{-*,}; do zgrep "finished cm/cm-" "$file" | while read line; do date -d "$(awk \'{print $1 " " $2 " " $3}\' <<< "$line")" "+%s $(grep -o "Client.*" <<< "$line")"; done; done > /var/log/nmsprime/tftpd-cm.log');
        chmod('/var/log/nmsprime/tftpd-cm.log', 0600);
        chown('/var/log/nmsprime/tftpd-cm.log', 'apache');
        chgrp('/var/log/nmsprime/tftpd-cm.log', 'apache');

        system('systemctl restart rsyslog.service');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
