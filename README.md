# React-PHP-ToDo
 Simple Todo app made with react.js and PHP

## Back-end
 You can use XMPP, MAMP or any PHP and MYSQL application for it.
 For setuping back end you can follow the steps in bellow;
 - Open phpmyadmin and create a database for the app
 - You can import the "dumpDB-todo.sql" file to database for dump data.
 - There is an index.php file in "API" file you can change the $dbHost, $dbName, $dbUser, $dbPass according to your own credentials.

## Front-end

In "react-todo" file there is an ReactJS app
You can setup the api path in "react-todo" > ".env" > "REACT_APP_ENDPOINT" variable

Installing commands;

```
cd react-todo
npm install
npm run build
npm start
```

## Using

There is a 2 pages for this application 

- "/" (home.js) for listing and adding to todos
- "/logins" for login with users

There is no register page for app yet, you can use database users table for adding new users

For adding new users should use MD5 hasing for passoword variable

## Licensing

There is no licensing for the app this app is made for learning purposes.
