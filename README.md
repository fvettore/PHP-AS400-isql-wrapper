###PHP AS400 isql wrapper###

####Introduction####

As reported elsewhere Iseries IBM ODBC 64 bit drivers are badly implemented and causes segmentation fault on NULL returned values with PHP-ODBC. It seems it was implemented on outdated Unix-ODBC specifications.
Since command line isql (iusql for UNICODE) tools works perfectly I decided to build a light&simple PHP wraper to query AS400 DB.

####Features:####

* query
* basic ODBC error handling
* fetch_array
* syntax similar to MySQLI 

####Prerequisites####
* Iseries ODBC driver installed
* ODBC DSN configured
* isql (iusql) installed

####Drawbacks####
NULL field value not detecetd. Relaced with ''

####Status####
Very initial stage.
Tested with Centos7 seems to be able to quickliy fetch tables with several thousands lines.

####DONATE####
If you can and find this useful:
Paypal: fabrizio (at) vettore.org
