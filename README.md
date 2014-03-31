README for AMUNCA
==================
AMUNCA ("AMUNCA Multi User Network Calendar Application") is an attempt
to create an easy to use calendar administration application. By
access through the web browser it is platform independent. Another
feature of AMUNCA are multi-user calendars which allow users to 
harmonize their arrangements easily.

Dependencies
-------------
A webserver capable of PHP and MySQL as well as CGI applications.
It is highly recommended to use SSL with this application.

Installing
-----------
1. To set up the AMUNCA login environment as well as the web gui, copy
the directory "frontend" to your webserver. You can rename it if you
want.
2. Open "mysql.php" in a text editor and insert correct login information
to your MySQL database.
3. Open /path/to/frontend/setup.php in a webbrowser and check if everything
has worked.
4. Once setup has finished successfully, you can visit 
/path/to/frontend/index.php and log in as default user "admin" with 
password "admin".


Licensing
----------

Developed by Johannes Bucher <http://github.com/johabu>
This file is part of AMUNCA.

AMUNCA is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
