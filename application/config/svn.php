<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* Usage:
    htpasswd [-cimBdpsDv] [-C cost] passwordfile username
    htpasswd -b[cmBdpsDv] [-C cost] passwordfile username password

    htpasswd -n[imBdps] [-C cost] username
    htpasswd -nb[mBdps] [-C cost] username password
     -c  Create a new file.
     -n  Don't update file; display results on stdout.
     -b  Use the password from the command line rather than prompting for it.
     -i  Read password from stdin without verification (for script usage).
     -m  Force MD5 encryption of the password (default).
     -B  Force bcrypt encryption of the password (very secure).
     -C  Set the computing time used for the bcrypt algorithm
         (higher is more secure but slower, default: 5, valid: 4 to 31).
     -d  Force CRYPT encryption of the password (8 chars max, insecure).
     -s  Force SHA encryption of the password (insecure).
     -p  Do not encrypt the password (plaintext, insecure).
     -D  Delete the specified user.
     -v  Verify password for the specified user.
    On other systems than Windows and NetWare the '-p' flag will probably not work.
    The SHA algorithm does not use a salt and is less secure than the MD5 algorithm.
 */
$config['svn']['htpasswd'] = 'htpasswd';

/**
 * general usage: svnauthz SUBCOMMAND TARGET [ARGS & OPTIONS ...]
 * svnauthz-validate TARGET
 *
 * If the command name starts with 'svnauthz-validate', runs in
 * pre-1.8 compatibility mode: run the 'validate' subcommand on TARGET.
 *
 * Type 'svnauthz help <subcommand>' for help on a specific subcommand.
 * Type 'svnauthz --version' to see the program version.
 *
 * Available subcommands:
 * help (?, h)
 * validate
 * accessof
 */
$config['svn']['svnauthz'] = 'svnauthz';

/**
 * general usage: svnadmin SUBCOMMAND REPOS_PATH  [ARGS & OPTIONS ...]
 * Type 'svnadmin help <subcommand>' for help on a specific subcommand.
 * Type 'svnadmin --version' to see the program version and FS modules.
 *
 * Available subcommands:
 * crashtest
 * create
 * deltify
 * dump
 * freeze
 * help (?, h)
 * hotcopy
 * list-dblogs
 * list-unused-dblogs
 * load
 * lock
 * lslocks
 * lstxns
 * pack
 * recover
 * rmlocks
 * rmtxns
 * setlog
 * setrevprop
 * setuuid
 * unlock
 * upgrade
 * verify
 */
$config['svn']['svnadmin'] = 'svnadmin';

/**
 * general usage: svnlook SUBCOMMAND REPOS_PATH [ARGS & OPTIONS ...]
 * Note: any subcommand which takes the '--revision' and '--transaction'
 * options will, if invoked without one of those options, act on
 * the repository's youngest revision.
 * Type 'svnlook help <subcommand>' for help on a specific subcommand.
 * Type 'svnlook --version' to see the program version and FS modules.
 *
 * Available subcommands:
 * author
 * cat
 * changed
 * date
 * diff
 * dirs-changed
 * filesize
 * help (?, h)
 * history
 * info
 * lock
 * log
 * propget (pget, pg)
 * proplist (plist, pl)
 * tree
 * uuid
 * youngest
 */
$config['svn']['svnlook'] = 'svnlook';

$config['svn']['repositories_path'] = 'F:\\Repositories';
$config['svn']['htpasswd_file'] = 'F:\\Repositories\\htpasswd';
$config['svn']['group_file'] = 'F:\\Repositories\\groups.conf';
$config['svn']['authz_file'] = 'VisualSVN-SvnAuthz.ini';
$config['svn']['authz_type'] = 'repository';
$config['svn']['server'] = 'https://10.30.130.223/svn/';

$config['svn']['folder_ignore'] = array(
    '.idea/', '.metadata/', ".settings/", "bin/", "gen/");

$config['svn']['admin'] = array(
    'admin' => array('name' => 'admin', 'pwd' => '21232f297a57a5a743894a0e4a801fc3', 'role' => 'super')
);