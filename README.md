README for AMUNCA
==================
AMUNCA ("AMUNCA Multi User Network Calendar Application") is an attempt to 
create an easy to use calendar administration application.
By access through the web browser its users are not bound to specific platforms.
 Another feature of AMUNCA are multi-user calendars which allow users
to harmonize their arrangements easily.
Dependencies
-------------
A webserver capable of PHP and MySQL.
It is highly recommended to use SSL with this application!
Otherwise, passwords could be easily sniffed out!

AMUNCA uses jQuery UI to create a well-styled and easy to use user interface.

Platforms
----------
AMUNCA should work on most systems running an web server with PHP and
MySQL supported, but it has been tested on GNU/Linux with kernel 3.11
(x86\_64) running Apache 2.4.6, PHP 5.5.3 and MySQL 5.5

To optimize AMUNCA's user interaction, make sure output\_buffering is enabled
in your php.ini

Installing
-----------
1. To set up the AMUNCA login environment as well as the web gui, copy
the directory "web" to your webserver. You can rename it if you
want.
2. In the directory create a new one called "jquery".
3. Download jQuery UI (<http://jqueryui.com>), best with the theme called
"smoothness" which looks well together with AMUNCA.
4. Copy the contents of your downloaded jqueryui-folder into the newly created
"jquery".
5. Open "mysql.php" in a text editor and insert correct login information
to your MySQL database.
6. Open /path/to/frontend/setup.php in a webbrowser and check if everything
has worked.
7. Once setup has finished successfully, you can log in as default user
 "admin" with password "admin".


Licensing
----------

Developed by Johannes Bucher <http://github.com/johabu>.
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
