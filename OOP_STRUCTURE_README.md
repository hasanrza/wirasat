# OOP Structure Documentation

## Overview
The application has been refactored to use **Object-Oriented Programming (OOP)** with **PDO** for database operations, following modern PHP best practices.

---

## Folder Structure

```
wirasat/
│
├── config/
│   ├── Database.php          # PDO Database connection (Singleton pattern)
│   └── autoload.php           # Auto-loads classes automatically
│
├── classes/
│   ├── Admin.php              # Admin model with CRUD operations
│   └── FormHandler.php        # Handles form validation and processing
│
├── controllers/
│   └── ProfileController.php  # Profile page business logic
│
├── include/
│   ├── db.php                 # Legacy mysqli connection (backward compatibility)
│   ├── header.php
│   ├── menu.php
│   └── ...
│
└── profile.php                # Profile page using OOP structure
```

---

## Key Components

### 1. **Database Class** (`config/Database.php`)
- Uses **Singleton pattern** to ensure single database connection
- Implements **PDO** for secure database operations
- Features:
  - Error handling with exceptions
  - Prepared statements by default
  - Connection pooling support

**Usage:**
```php
$database = Database::getInstance();
$db = $database->getConnection();
```

### 2. **Admin Class** (`classes/Admin.php`)
Handles all admin-related database operations.

**Methods:**
- `getById($id)` - Get admin by ID
- `getByEmail($email)` - Get admin by email
- `emailExists($email, $excludeId)` - Check if email exists
- `updateProfile($id, $fname, $lname, $email)` - Update profile
- `updatePassword($id, $newPassword)` - Update password
- `verifyPassword($password, $hashedPassword)` - Verify password
- `validateEmail($email)` - Validate email format
- `validatePassword($password, $minLength)` - Validate password strength
- `login($email, $password)` - Authenticate admin

**Usage:**
```php
$adminObj = new Admin($db);
$admin = $adminObj->getById($id);
$adminObj->updateProfile($id, $fname, $lname, $email);
```

### 3. **FormHandler Class** (`classes/FormHandler.php`)
Manages form validation and error/success messages.

**Methods:**
- `addError($message)` - Add error message
- `addSuccess($message)` - Add success message
- `hasErrors()` - Check if errors exist
- `getFirstError()` - Get first error
- `getFirstSuccess()` - Get first success message
- `sanitize($data)` - Sanitize input
- `validateRequired($value, $fieldName)` - Validate required field
- `validateEmail($email)` - Validate email
- `validateMinLength($value, $minLength, $fieldName)` - Validate length
- `validatePasswordMatch($password, $confirmPassword)` - Match passwords
- `handleProfileUpdate($adminObj, $postData, $adminId)` - Process profile update
- `handlePasswordUpdate($adminObj, $postData, $adminId, $currentHashedPassword)` - Process password update

**Usage:**
```php
$formHandler = new FormHandler();
$formHandler->handleProfileUpdate($adminObj, $_POST, $adminId);
$error = $formHandler->getFirstError();
```

### 4. **ProfileController Class** (`controllers/ProfileController.php`)
Coordinates between Admin and FormHandler classes for profile operations.

**Methods:**
- `__construct($admin, $formHandler)` - Initialize controller
- `loadAdminData($adminId)` - Load admin data from database
- `getAdminData()` - Get loaded admin data
- `processRequest($adminId)` - Process form submissions
- `getSuccessMessage()` - Get success message
- `getErrorMessage()` - Get error message

**Usage:**
```php
$profileController = new ProfileController($adminObj, $formHandler);
$profileController->loadAdminData($admin_id);
$profileController->processRequest($admin_id);
```

---

## How It Works

### Profile Page Flow (`profile.php`)

1. **Initialization:**
   ```php
   require_once 'config/autoload.php';
   $database = Database::getInstance();
   $db = $database->getConnection();
   ```

2. **Create Objects:**
   ```php
   $adminObj = new Admin($db);
   $formHandler = new FormHandler();
   $profileController = new ProfileController($adminObj, $formHandler);
   ```

3. **Load Data:**
   ```php
   $profileController->loadAdminData($admin_id);
   $admin = $profileController->getAdminData();
   ```

4. **Process Forms:**
   ```php
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $profileController->processRequest($admin_id);
   }
   ```

5. **Get Messages:**
   ```php
   $success_message = $profileController->getSuccessMessage();
   $error_message = $profileController->getErrorMessage();
   ```

---

## Security Features

✅ **PDO Prepared Statements** - Prevents SQL injection  
✅ **Password Hashing** - Uses `password_hash()` with `PASSWORD_DEFAULT`  
✅ **Input Sanitization** - Strips tags and special characters  
✅ **XSS Protection** - Uses `htmlspecialchars()` on output  
✅ **Email Validation** - Validates email format  
✅ **Password Strength** - Minimum 6 characters  
✅ **Session Management** - Secure login verification  

---

## Benefits of OOP Structure

1. **Separation of Concerns** - Business logic separated from presentation
2. **Reusability** - Classes can be reused across different pages
3. **Maintainability** - Easy to update and debug
4. **Testability** - Can unit test individual classes
5. **Scalability** - Easy to extend functionality
6. **Security** - Centralized security practices
7. **Clean Code** - Readable and organized

---

## Extending the System

### Add New Methods to Admin Class:
```php
public function deleteAdmin($id) {
    $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}
```

### Create New Controllers:
```php
// controllers/DashboardController.php
class DashboardController {
    private $admin;
    
    public function __construct($admin) {
        $this->admin = $admin;
    }
    
    public function getDashboardData() {
        // Logic here
    }
}
```

### Use Autoloader:
All classes in `config/`, `classes/`, and `controllers/` folders are automatically loaded!

---

## Migration Notes

- **Backward Compatibility:** Old mysqli connection still available in `include/db.php`
- **Gradual Migration:** Can migrate pages one at a time to OOP structure
- **No Breaking Changes:** Existing pages continue to work

---

## Database Configuration

Update database credentials in `config/Database.php`:
```php
private $host = 'localhost';
private $db_name = 'ereal';
private $username = 'root';
private $password = '';
```

---

## Form Submission Examples

### Update Profile:
```html
<form method="POST" action="">
    <input type="text" name="first_name" required>
    <input type="text" name="last_name" required>
    <input type="email" name="email" required>
    <button type="submit" name="update_profile">Update</button>
</form>
```

### Update Password:
```html
<form method="POST" action="">
    <input type="password" name="current_password" required>
    <input type="password" name="new_password" required>
    <input type="password" name="confirm_password" required>
    <button type="submit" name="update_password">Update</button>
</form>
```

---

## Error Handling

All database errors are logged and user-friendly messages are displayed:
- Profile update errors
- Password validation errors
- Email duplication errors
- Database connection errors

---

## Future Enhancements

- [ ] Add admin roles and permissions
- [ ] Implement email notifications
- [ ] Add profile picture upload
- [ ] Create admin activity logs
- [ ] Add two-factor authentication
- [ ] Implement password reset functionality

---

**Created:** November 24, 2025  
**Last Updated:** November 24, 2025

