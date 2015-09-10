# SubdomainFind
A web utility written in php and using mysql Database
subdomain finder v1.0

subdomain finder is web based utility to find out the subdomains of a domain.
It uses bruteforce method to get the subdomains.

how to set it up ?
For testing purpose you can set it up on your windows machine.
Step1:
install xampp in your machine .
step2:
start apache and mysql service from xampp control panel.
Step3:
configure mysql account in your phpmyadmin.
step4:
create a database of name newdata.
step5:
import newdata.sql file into your database newdata.
copy all the files of code folder into c:\xampp\htdocs\subdomain folder (first create subdomain folder in c:\xampp\htdocs folder) 
step7:
Edit connect.php file
change value of $db_user and $db_pass according to your credentials of phpmyadmin.
step8:
open localhost/subdomain/index.html in your browser and use it.


for any query email me at 1308js@gmail.com