# Task Manager Web Application

This project is a simple web application that allows users to manage a task list. The application uses Laravel for the backend and Vue.js for the frontend.
The application was made for the sole purpose to prove one's skills and understanding of the technologies used.

---

## Features

- **User Registration & Login**: Authentication system implemented using Laravel's built-in authentication.
- **Dashboard**:
  - Confirm Login
  - Gateway
- **Task Manager**:
  - Add new tasks with a title and description.
  - View a list of tasks.
  - Mark tasks as completed.
  - Mask completed Tasks.
  - Update Tasks.
  - Delete tasks.
- **Validation**:
  - Task titles must be unique.
- **API Endpoints**: CRUD operations for managing tasks.
- **Frontend Navigation**: Smooth and responsive navigation using Vue.js.
- **Best Practices**: Organized and secure code following Laravel, Tailwind and Vue.js standards.

---

## Setup Instructions

### Prerequisites
- PHP 8.x
- Composer
- Node.js and npm

### Installation Steps

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/MedAliOrh/task-list.git
   cd task-list
   ```

2. **Install Backend Dependencies**:
   ```bash
   composer install
   ```

3. **Set Up the Environment**:
   - Copy the `.env.example` file:
     ```bash
     cp .env.example .env
     ```
   - Update the `.env` file with your database credentials.
   - packages .json should contain the correct versions of the packages.

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Run Migrations**:
   ```bash
   php artisan migrate
   ```

6. **Install Frontend Dependencies**:
   ```bash
   npm install
   ```

7. **Build Frontend Assets**:
   ```bash
   npm run dev
   ```

8. **Start the Server**:
   ```bash
   php artisan serve
   ```

9. **Access the Application**:
   - Visit `http://localhost:8000` in your browser.

---

## API Endpoints

### Task Management
| Method | Endpoint           | Description          |
|--------|--------------------|----------------------|
| GET    | `/api/tasks`       | Fetch all tasks      |
| POST   | `/api/tasks`       | Create a new task    |
| PUT    | `/api/tasks/{id}`  | Update a task        |
| DELETE | `/api/tasks/{id}`  | Delete a task        |
| PUT    | `/api/tasks/{id}/complete` | Mark task as complete |

---

## Expectations

- **Code Quality**: Organized and follows best practices, should get a good score on SonarQube.
- **Functionality**: Complete implementation of requirements.
- **User Experience**: Responsive, simple and smooth interface, with a help feature.
- **Security**: Authentication is comfort to the default laravel auth system, validation, and error handling are responsive.
- **Creativity**: user-friendly features, Tailwind manipulation, more icons from fontawsome.

---

Thank you for reviewing this project!