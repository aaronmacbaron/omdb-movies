# Preface to why i have done it this way
I decided to take on both the assignments as a way to show my versatility, albeit I took a much more compact approach on the front-end. Initially I had built it with react-redux and redux-thunk to handle the one API request. But I felt that was overkill to handle such a simple task. I then started to build the backend and realized that wiring all of it together would take much more time than the 3 days I have been allotted. So I ended up scrapping my redux approach for something a bit more primitive. 

In the end, it still solves the problem required on the frontend but with less bulky code. I have also slightly modified the implementation to hit my backend REST API server instead of the OMDB API directly. The Laravel REST API server acts as a middleman between the front-end and the OMDB API. 

The role that the backend plays is that it initially looks in the MySQL database for search results, if none are found then it queries the OMDB API, saves a copy of the data and sends it back to the front-end. This saving allows the data to be modifiable by the user. 

On the frontend you can search through the OMDB API by simply using the search bar at the top and hitting enter or pressing search. When finished, clicking on a movie title will allow you to see the details of that movie on a separate page to be able to update the details, or delete it from the database. Creation of a movie can only be performed using a POSTMAN request for this project, the backend implementation is there, but I couldn't find the time for it. On a side note I am already doing creation when data is inserted from the OMDB API to MySQL. If testing is needed you only need to send a POST request to `/api/movies` with the details of the movie you want to add.

# Requirements to run this code

**Software requirements**: 

 - PHP 8(I'm using Xampp)
 - Composer (getcomposer.org)
 - MySQL
 - Node 14.17.3
 - NPM 6.14.13
 
**Software I am Using**
 - HeidiSQL - For accessing database tables to view and verify CRUD operations
 - XAMPP - For the localized PHP installation
 - VS Code - Main IDE of choice for the last couple years
 - POSTMAN - To run POST, PATCH, GET, DELETE requests to the laravel REST API

**Database** : 

 - Need a running mysql database with a user "root" and an empty password. (These can be changed in the laravel .env file.
 -  In the movies-backend directory you have to run `php artisan migrate` this will run all the database migrations.

**PHP** :

 - In order to run the project you have to first run a few commands in a terminal window
 those are as follows:  `composer install` then `php artisan key:generate` then `php artisan storage:link`
- After that you'll have to keep a terminal running with the command `php artisan serve`
this will run the Laravel backend at localhost on port 8000. Accessible via http://localhost:8000

**React**

 - For the React side you'll first have to run `npm install` then `npm start`
 - This will serve the app on localhost at port 3000. Accessible via http://localhost:3000
