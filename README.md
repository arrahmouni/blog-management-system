# Blog Management System

A full-stack blogging platform built with Laravel 12 and Vue 3 (Composition API). It provides an API-driven backend with role-based access control (Admin, Writer, User) and a Vue.js front-end without Inertia, enabling the API to be used by other clients if needed.

## Features

* **User Roles**: Admin, Writer, and User
* **Authentication**: API authentication via Sanctum
* **Posts**: Create, update, delete (soft deletes), publish/draft, attach cover images
* **Categories**: Admin can manage categories; posts may belong to multiple categories
* **Comments**: All roles can comment; Admin can approve/delete; live broadcasting of new comments
* **Activity Logging**: Track all model changes via Spatie Activitylog
* **Media Uploads**: File storage with Spatie MediaLibrary
* **Broadcasting**: Real-time notifications with Laravel Reverb
* **API Documentation**: Postman Collection included

## Prerequisites

* PHP >= 8.1
* Composer
* MySQL (or compatible database)
* Node.js >= 16
* npm or yarn

## Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/arrahmouni/blog-management-system
   cd blog-management-system
   ```

2. **Copy and configure environment variables**

   ```bash
   cp .env.example .env
   ```

   Edit `.env` to set up your database credentials and other services:

3. **Install PHP dependencies**

   ```bash
   composer install
   ```

4. **Install JavaScript dependencies**

   ```bash
   npm install
   # or
   yarn install
   ```

5. **Generate application key**

   ```bash
   php artisan key:generate
   ```

6. **Run migrations and seeders**

   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Build assets**

   ```bash
   npm run dev
   # or
   yarn dev
   ```

8. **Serve the application**

   * **Backend**: `php artisan serve` (defaults to [http://127.0.0.1:8000](http://127.0.0.1:8000))
   * **Frontend**: Vite dev server runs automatically at [http://localhost:5173](http://localhost:5173) (configured in `vite.config.js`)

9. **Run the queue work**
    ```bash
    php artisan queue:work
    ```

10. **Start the Reverb broadcaster**
    ```bash
    php artisan reverb:start
    ```

## Authentication & Access

Two separate login pages:

* **Admin Panel**:
  URL: `/admin/login`
  Controller method: `AuthController@loginToAdminPanel`
* **User Portal**:
  URL: `/login`
  Controller method: `AuthController@login`

**Seeded users**:

| Role   | Email                                           | Password    |
| ------ | ----------------------------------------------- | ----------- |
| Admin  | [admin@example.com](mailto:admin@example.com)   | password123 |
| Writer | [writer@example.com](mailto:writer@example.com) | password123 |
| User   | [user@example.com](mailto:user@example.com)     | password123 |

## API Endpoints

All routes are prefixed with `/api` and protected via Sanctum where noted.

### Authentication

| Method | Endpoint           | Description                      | Auth    |
| ------ | ------------------ | -------------------------------- | ------- |
| POST   | `/register`        | Register a new user              | Public  |
| POST   | `/login`           | User login                       | Public  |
| POST   | `/admin/login`     | Admin Panel login                | Public  |
| POST   | `/logout`          | Logout                           | Sanctum |
| POST   | `/forgot-password` | Send reset link (throttle 3/min) | Public  |
| POST   | `/reset-password`  | Reset password                   | Public  |
| GET    | `/user`            | Get authenticated user info      | Sanctum |

### Categories (Admin)

| Method | Endpoint                 | Description              | Auth    |
| ------ | ------------------------ | ------------------------ | ------- |
| GET    | `/category`              | List categories          | Sanctum |
| GET    | `/category/{id}`         | Get a category           | Sanctum |
| POST   | `/category`              | Create new category      | Sanctum |
| POST   | `/category/{id}`         | Update a category        | Sanctum |
| DELETE | `/category/{id}`         | Soft delete a category   | Sanctum |
| POST   | `/category/{id}/restore` | Restore soft-deleted     | Sanctum |
| DELETE | `/category/{id}/force`   | Force delete permanently | Sanctum |
| GET    | `/category/{id}/logs`    | Activity logs            | Sanctum |

### Posts

| Method | Endpoint             | Description               | Auth    |
| ------ | -------------------- | ------------------------- | ------- |
| GET    | `/post`              | List posts                | Sanctum |
| GET    | `/post/{id}`         | Get post                  | Sanctum |
| POST   | `/post`              | Create post               | Sanctum |
| POST   | `/post/{id}`         | Update post               | Sanctum |
| PUT    | `/post/{id}/approve` | Approve post (Admin only) | Sanctum |
| DELETE | `/post/{id}`         | Soft delete post          | Sanctum |
| POST   | `/post/{id}/restore` | Restore post              | Sanctum |
| DELETE | `/post/{id}/force`   | Force delete              | Sanctum |
| GET    | `/post/{id}/logs`    | Activity logs             | Sanctum |

### Comments

| Method | Endpoint                         | Description             | Auth    |
| ------ | -------------------------------- | ----------------------- | ------- |
| GET    | `/post/{post}/comment`           | List comments on a post | Sanctum |
| GET    | `/post/{post}/comment/{comment}` | Get a comment           | Sanctum |
| POST   | `/post/{post}/comment`           | Add a new comment       | Sanctum |
| PUT    | `/comment/{id}/accept`           | Approve comment (Admin) | Sanctum |
| DELETE | `/comment/{id}`                  | Soft delete comment     | Sanctum |

### Public Home Endpoints

| Method | Endpoint                | Description          | Auth   |
| ------ | ----------------------- | -------------------- | ------ |
| GET    | `/posts-list`           | List published posts | Public |
| GET    | `/categories-list`      | List all categories  | Public |
| GET    | `/authors-list`         | List authors         | Public |
| GET    | `/post-details/{slug}`  | Post detail by slug  | Public |
| GET    | `/post-comments/{post}` | Comments by post     | Public |

### Upgrade Requests (Admin)

| Method | Endpoint                       | Description                | Auth    |
| ------ | ------------------------------ | -------------------------- | ------- |
| GET    | `/upgrade-request`             | List requests              | Sanctum |
| GET    | `/upgrade-request/get/status`  | Get current upgrade status | Sanctum |
| POST   | `/upgrade-request/apply`       | Apply for role upgrade     | Sanctum |
| POST   | `/upgrade-request/{id}/accept` | Accept upgrade (Admin)     | Sanctum |
| POST   | `/upgrade-request/{id}/reject` | Reject upgrade (Admin)     | Sanctum |

## Frontend (Vue 3)

* Located in `resources/js` (Laravel) and compiled with Vite
* Vue Router handles client routes
* Forms built with `vue-yup-form` and input masks via `vue-the-mask`
* UI styled with Tailwind CSS
* Components under `admin/` for Admin Panel and `frontend/` for User Portal

### Admin Panel Routes

* `/admin/login`
* `/admin/dashboard`
* `/admin/categories` (Admin only)
* `/admin/posts` (Admin & Writer)
* `/admin/upgrade-requests` (Admin only)

### User Portal Routes

* `/` (Home)
* `/posts`
* `/post/:slug`
* `/login`
* `/register`
* `/forgot-password`
* `/reset-password`

## Running Tests

> **Coming soon**: Test suites will be implemented in the next iteration.

## Postman Collection

A Postman collection is included at `docs/Blog System.postman_collection.json`.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss.

## License

This project is open-sourced under the MIT license.
