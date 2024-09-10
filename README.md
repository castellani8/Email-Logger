<h1 align="center">Email Formatter</h1>

## How it works

This system was built with Laravel in order to store and manipulate emails, formatting their content.
A cron was configured to run the command every hour in the server.

## Installing locally

First of all you need to have docker installed on your computer, you can find more information in this link: <a href="https://docs.docker.com/engine/install/">Install Docker</a>.

- Just copy the .env.example file and rename the copied file to .env
- Run the following command ``` docker-compose up --build -d ```
- The application will now start running on your localhost at the port 8005.

## Details
- The PHP container will automatically run ``` php /var/www/html/artisan schedule:run ``` every 15 minutes through cron, and that will execute all the commands in the application that have been scheduled.
- The command ``` app:format-email-body ``` command have been scheduled to run every hour, with the purpose to format the emails.

## Endpoints

### Base Environment For Doing Requests
- **Base Local URL:** `localhost:8005`
- **Headers:** (Required for all endpoints except Get Token)
    - `Content-Type: application/json`
    - `User-Agent: insomnia/8.5.1`
    - `Accept: application/json`
    - `Authorization: Bearer {token}`

### 1. Get Token
- **Method:** `GET`
- **URL:** `{{ _.url }}/api/login`
- **Description:** Retrieves an authentication token.
- **Body:**
  ```json
  {
    "email": "test@example.com",
    "password": "password"
  }

### 2. Create Record
- **Method:** `POST`
- **URL:** `{{ _.url }}/api/store`
- **Description:** Creates a new record in the system.
- **Authorization:** Bearer {{ _.token }}
- **example body:**
  ```json 
  {
     "affiliate_id": 1,
     "envelope": "{\"sender\":\"sender@example.com\",\"recipient\":\"recipient@example.com\"}",
     "from": "sender@example.com",
     "subject": "Example Subject",
     "dkim": "pass",
     "SPF": "pass",
     "spam_score": 0.5,
     "email": "<html>dsadasdas<body><h1>Example Email</h1><p>This is an example email body.</p></body></html>",
     "sender_ip": "192.168.1.1",
     "to": "recipient@example.com",
     "timestamp": 1693843200
  }
      
### 3. Show Record
- **Method:** `GET`
- **URL:** `{{ _.url }}/api/getById/{id}`
- **Description:** Retrieves a record by its ID.

### 4. Update Record
- **Method:** `PUT`
- **URL:** `{{ _.url }}/api/update/{id}`
- **Description:** Updates an existing record by id.
- **Example body**:
  ```json
  {
     "affiliate_id": 23,
     "email": "<html><title>My test<title><body><h1>Example Email:</h1> This is an example email body.</p></body></html>"
  }
You can add the fields you want to update in the json.

### 5. Delete Record
- **Method:** `DELETE`
- **URL:** `{{ _.url }}/api/deleteById/{id}`
- **Description:** Deletes a record by its ID.

### 6. Get All Records
- **Method:** `GET`
- **URL:** `{{ _.url }}/api/getAll`
- **Description:** Gets all records
