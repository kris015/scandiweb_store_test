If you run the project on localhost: e.g. Xampp: URL of the backend (required for fetching from the frontend) will be localhost/naziv_root_direktorijuma/backend/index.php

The connection to the MySQL database is on the path: backend/config/database.php, and the sql file (Filled with data as requested in the task) that you can import to phpmyadmin is located here in the root directory called 'scandiweb_store.sql'.

Frontend:
- On the /frontend/src/apolloClient.js path, change the path that leads to your backend/index.php file.
- If you start locally, navigate to the /frontend/ folder in the command prompt, use the command 'npm run dev' to start the React project, with Apache and MySQL previously activated on Xamp/Wamp or the server of your choice. And you can access the application via the URL you get by running the react project.

I will also leave images in the root directory as proof of the correctness of the project.
