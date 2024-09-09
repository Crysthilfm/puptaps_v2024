# puptaps
 
=====How to install=====
1. Clone 
2. Go to puptaps location via terminal/cmd 
3. run "composer install"
4. run "copy .env.example .env"
5. create database in xampp - puptaps_db
6. change the DB_DATABASE in .env file to puptaps_db 
7. FOR THE MAIL CREDENTIALS IN .env FILE refer to GMAIL of the system
8. run "php artisan key:generate"
9. run "php artisan migrate"
10. import the courses to the courses table in the database
11. run "php artisan serve"

=====How to comment CSS=====
1. write the location of the class
ex. News Section->container->row->col
if there are many div inside section use the div class instead. 
2. check format sample in the Public->CSS 
3. all css inside the system (after login) should be inside styles.css
4. create another css if necessary 
5. ALL IMAGES SHOULD BE ROUTED INSIDE THE BLADE.PHP FILE NOT IN THE CSS


=====REMINDER=====
1. BEFORE PUSHING IN GITHUB PLEASE PULL THE FILES FIRST THEN RUN AGAIN THE SYSTEM, CHECK IF THERE IS NO ERROR, THEN PUSH.
2. LEAVE A MESSAGE IN GC BEFORE PUSHING
3. LEAVE A COMMENT IF NECESSARY


