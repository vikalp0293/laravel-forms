# Laravel 11 Bootstrap Authentication Example

To use the Laravel 11 Bootstrap Auth Scaffolding Example from the repository https://github.com/tutsmake/Laravel-11-Bootstrap-Auth-Scaffolding-Example-Tutorial, you can follow these steps:

# Clone the Repository

<pre> git clone https://github.com/tutsmake/Laravel-11-Bootstrap-Auth-Scaffolding-Example-Tutorial.git</pre>

# Navigate to the Project Directory

<pre> cd Laravel-11-Bootstrap-Auth-Scaffolding-Example-Tutorial </pre>

# Install Dependencies

<pre> composer install </pre>

# Copy the Environment File

<pre>cp .env.example .env </pre>

# Generate Application Key

<pre> php artisan key:generate</pre>

# Configure Database

Open the .env file and set your database credentials.

# Run Migrations

<pre> php artisan migrate</pre>

# Run Seeders

Optionally, you can seed the database with dummy data:

<pre>php artisan db:seed</pre>

# Install npm dependencies and compile assets:

<pre> npm install && npm run dev </pre>

# Start the Development Server

<pre> php artisan serve</pre>

# Access the Application:

You should now be able to access the application in your browser at http://localhost:8000. The authentication views should be styled using Bootstrap.

# Published By

https://www.tutsmake.com/

# Youtube Video
https://youtu.be/DA8Kz7_85V0
