# PHP Blog Project with REST API

A simple PHP-based blog application with CRUD functionality and a RESTful API for managing blog posts. 

## Features
- Create, Read, Update, and Delete (CRUD) blog posts.
- RESTful API to manage blog posts.
- Stores blog posts in a MySQL database.
- Includes features like validation (e.g., limiting titles to 50 characters).
- Uploads and stores images for each blog post.
- Tracks writer names for each blog entry.

---

## How to Containerize the Application Using Docker

### Step 1: Create a Dockerfile
Create a `Dockerfile` in the root directory of your project with the following content:

```dockerfile
# Use the official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install zip

# Install PHP extensions for MySQL (mysqli, pdo, pdo_mysql)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set the working directory for Apache
WORKDIR /var/www/html

# Copy the project files into the container
COPY . /var/www/html/

# Copy custom Apache configuration
COPY 000-default.conf /etc/apache2/sites-available/

# Set file permissions so Apache can serve the files correctly
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80 for the web server
EXPOSE 80

# Start Apache in the foreground (default for this image)
CMD ["apache2-foreground"]
```

### Step 2: Write a `docker-compose.yml` File
Create a `docker-compose.yml` file to run the PHP application, MySQL database, and the Apache web server.

```yaml
version: '3.8'

services:
  php-apache:
    build:
      context: .
      dockerfile: Dockerfile
    ports:
      - "8080:80"
    volumes:
      - ./public:/var/www/html/public
      - ./api:/var/www/html/api
      - ./includes:/var/www/html/includes
      - ./style:/var/www/html/style
      - ./uploads:/var/www/html/uploads
      - ./index.php:/var/www/html/index.php
      - ./page.php:/var/www/html/page.php
      - ./post.php:/var/www/html/post.php
      - ./000-default.conf:/etc/apache2/sites-available/000-default.conf
    environment:
      - DB_HOST=db
      - DB_NAME=${DB_NAME}
      - DB_USER=${DB_USER}
      - DB_PASSWORD=${DB_PASSWORD}
    depends_on:
      - db
    networks:
      - app-network

  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_PASSWORD}
      MYSQL_DATABASE: ${DB_NAME}
      MYSQL_USER: ${DB_USER}
      MYSQL_PASSWORD: ${DB_PASSWORD}
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql
      - ./blog_database.sql:/docker-entrypoint-initdb.d/blog_database.sql
    networks:
      - app-network

volumes:
  db_data:

networks:
  app-network:
    driver: bridge
```

---

## Set Up a Basic CI/CD Pipeline

### Step 1: GitHub Workflow for CI/CD
Create a workflow file `.github/workflows/docker-ci.yml` with the following content:

```yaml
name: Docker Image CI/CD

on:
  push:
    branches: ["main"]
  pull_request:
    branches: ["main"]

jobs:
  build-and-push-image:
    runs-on: ubuntu-latest

    permissions:
      contents: read
      packages: write

    steps:
      - name: Checkout repository
        uses: actions/checkout@v3

      - name: Set up Docker Buildx
        uses: docker/setup-buildx-action@v2

      - name: Log in to GitHub Container Registry
        uses: docker/login-action@v2
        with:
          registry: ghcr.io
          username: ${{ github.actor }}
          password: ${{ secrets.GHCR_TOKEN }}

      - name: Build and push Docker image
        uses: docker/build-push-action@v4
        with:
          context: .
          push: true
          tags: ghcr.io/${{ github.repository }}:latest

  deploy:
    needs: build-and-push-image
    runs-on: ubuntu-latest
    steps:
      - name: Deploy Application
        run: |
          echo "Deploying application..."
          # Add your deployment commands here
```

### Step 2: Configure Secrets
- Add a secret named `GHCR_TOKEN` to your GitHub repository for authenticating with GitHub Container Registry.

---

## Run Locally

### Step 1: Build and Start Containers
Run the following commands:

```bash
docker-compose build
docker-compose up
```

### Step 2: Access the Application
Visit `http://localhost:8080` in your browser.

---

## API Documentation

The API endpoint for managing blog posts:  
`http://blog_project.test/api/blog.php`

### Endpoints

#### 1. **Fetch All Posts**
- **URL:** `/api/blog.php`
- **Method:** `GET`
- **Response:**  
  Returns all blog posts.

#### 2. **Fetch a Single Post**
- **URL:** `/api/blog.php?id={id}`
- **Method:** `GET`
- **Response:**  
  Returns a single post with the specified ID.

#### 3. **Create a New Post**
- **URL:** `/api/blog.php`
- **Method:** `POST`
- **Request Body (JSON):**
  ```json
  {
    "title": "Your Title",
    "content": "Post Content",
    "writer_name": "Writer Name"
  }
