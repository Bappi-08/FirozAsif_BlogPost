# PHP Blog Project with REST API

A simple PHP-based blog application with CRUD functionality and a RESTful API for managing blog posts. 

## Features
- Create, Read, Update, and Delete (CRUD) blog posts.
- RESTful API to manage blog posts.
- Stores blog posts in a MySQL database.
- Includes features like validation (e.g., limiting titles to 50 characters).
- Uploads and stores images for each blog post.
- Tracks writer names for each blog entry.

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
