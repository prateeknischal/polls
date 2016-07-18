###################
Polls
###################

Polls is a simple PHP application built in `CodeIgnitor Framework<https://www.codeigniter.com/>`_.
This is can be used to take notes, make a TO-DO list or create a poll, if you are in a dilemma.

*******************
Server Requirements
*******************

PHP version 5.4 or newer is recommended.

It should work on 5.2.4 as well, but we strongly advise you NOT to run
such old versions of PHP, because of potential security and performance
issues, as well as missing features.

*****************
Before setting up
*****************
1. Standard protocols have been followed regarding the codeignitor framework. Installed in the /var/www/html/ directory of the system root.
2. Requires changes in the application/config/database.php for username and password of the database being used.
3. Requires the walmart api key to be defined in application/config/constants.php line 88 constant “API_KEY”
4. The app entry point is “polls/index.php/login” as defined in the application/config/routes.php file.
5. Before using the app, “php5-curl” must be installed as the project uses “cURL” library for making GET requests to the walmart labs api.
