### abount me
The svn web admin application is a web based GUI to manage your svn. It requires a web server (Apache) to be installed. The application doesn’t need a database back end or anything similar, it is completely based on the Subversion authorization- and user authentication file.

### install
* checkout the source code 
* edit the svn.php on `/application/config/svn.php` 
  * $config['svn']['repositories_path'] = 'E:\\Repositories'; 
  * $config['svn']['htpasswd_file'] = 'E:\\Repositories\\htpasswd'; 
  * $config['svn']['group_file'] = 'E:\\Repositories\\groups.conf'; 
  * $config['svn']['authz_file'] = 'VisualSVN-SvnAuthz.ini'; 
  * $config['svn']['authz_type'] = 'repository'; 
  * $config['svn']['server'] = 'https://localhost/svn/'; 