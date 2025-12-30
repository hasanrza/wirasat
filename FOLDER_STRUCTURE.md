# Project Folder Structure - OOP Implementation

## 📁 Complete Directory Tree

```
wirasat/
│
├── 📁 config/                          # Configuration files
│   ├── Database.php                    # PDO Database connection (Singleton)
│   └── autoload.php                    # Auto-loads classes from all folders
│
├── 📁 classes/                         # Model classes
│   ├── Admin.php                       # Admin model with CRUD operations
│   └── FormHandler.php                 # Form validation and processing
│
├── 📁 controllers/                     # Controller classes (Business Logic)
│   ├── ProfileController.php           # Profile page controller
│   └── AuthController.php              # Authentication controller
│
├── 📁 include/                         # Legacy and shared includes
│   ├── db.php                          # Legacy mysqli connection
│   ├── database_setup.sql              # Database schema
│   ├── header.php                      # Page header
│   ├── menu.php                        # Navigation menu
│   ├── header_cdn.php                  # CDN links
│   └── footer_cdn.php                  # Footer CDN scripts
│
├── 📁 assets/                          # Static assets
│   ├── 📁 css/                         # Stylesheets
│   ├── 📁 js/                          # JavaScript files
│   ├── 📁 images/                      # Images
│   ├── 📁 fonts/                       # Web fonts
│   └── 📁 libs/                        # Third-party libraries
│
├── 📄 profile.php                      # Profile page (OOP version) ✨
├── 📄 login.php                        # Login page (OOP version) ✨
├── 📄 logout.php                       # Logout functionality
├── 📄 check_login.php                  # Login verification (Updated) ✨
├── 📄 register_admin.php               # Admin registration
├── 📄 index.php                        # Dashboard/Home page
│
├── 📄 about_us.php                     # About Us page
├── 📄 company_profile.php              # Company Profile page
├── 📄 ceo_message.php                  # CEO Message page
├── 📄 our_services.php                 # Our Services page
├── 📄 projects.php                     # Projects page
├── 📄 news_updates.php                 # News & Updates page
├── 📄 contact_us.php                   # Contact Us page
├── 📄 gallery.php                      # Gallery page
├── 📄 gallery_pictures.php             # Gallery Pictures page
├── 📄 gallery_videos.php               # Gallery Videos page
│
├── 📄 OOP_STRUCTURE_README.md          # OOP documentation ✨
└── 📄 FOLDER_STRUCTURE.md              # This file ✨

✨ = Newly created or updated files
```

---

## 📊 File Relationships

```
┌─────────────────────────────────────────────────────────────┐
│                       profile.php                            │
│                    (Main Entry Point)                        │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ├─► check_login.php (Session Check)
                     │
                     ├─► config/autoload.php
                     │        │
                     │        ├─► config/Database.php
                     │        ├─► classes/Admin.php
                     │        ├─► classes/FormHandler.php
                     │        └─► controllers/ProfileController.php
                     │
                     ├─► include/header_cdn.php
                     ├─► include/header.php
                     ├─► include/menu.php
                     └─► include/footer_cdn.php
```

---

## 🎯 Key Components by Folder

### 📁 config/
**Purpose:** Configuration and initialization files

| File | Description | Key Features |
|------|-------------|--------------|
| `Database.php` | PDO connection manager | Singleton pattern, connection pooling |
| `autoload.php` | Class autoloader | Auto-loads from classes/, config/, controllers/ |

---

### 📁 classes/
**Purpose:** Model classes for data and business logic

| File | Description | Responsibilities |
|------|-------------|------------------|
| `Admin.php` | Admin model | CRUD operations, authentication, validation |
| `FormHandler.php` | Form processor | Validation, sanitization, error handling |

**Admin.php Methods:**
- Database operations (getById, getByEmail)
- Profile management (updateProfile, updatePassword)
- Authentication (login, verifyPassword)
- Validation (validateEmail, validatePassword)

**FormHandler.php Methods:**
- Message management (addError, addSuccess)
- Input sanitization (sanitize)
- Field validation (validateRequired, validateEmail, validateMinLength)
- Form processing (handleProfileUpdate, handlePasswordUpdate)

---

### 📁 controllers/
**Purpose:** Controller classes for application logic

| File | Description | Manages |
|------|-------------|---------|
| `ProfileController.php` | Profile page logic | Profile/password updates, data loading |
| `AuthController.php` | Authentication logic | Login, logout, session management |

**ProfileController.php:**
- Coordinates between Admin and FormHandler
- Loads and manages admin data
- Processes form submissions
- Returns success/error messages

**AuthController.php:**
- Handles login process
- Validates credentials
- Manages sessions
- Provides authentication status

---

## 🔄 Data Flow Example

### Profile Update Flow:

```
1. User submits form
   ↓
2. profile.php receives POST data
   ↓
3. ProfileController.processRequest()
   ↓
4. FormHandler.handleProfileUpdate()
   ├─► Validates input
   ├─► Sanitizes data
   └─► Checks email uniqueness
   ↓
5. Admin.updateProfile()
   ├─► Prepares SQL statement
   ├─► Binds parameters (PDO)
   └─► Executes update
   ↓
6. Returns success/error
   ↓
7. Display message to user
```

### Login Flow:

```
1. User submits login form
   ↓
2. login_oop.php receives POST data
   ↓
3. AuthController.login()
   ↓
4. FormHandler validates input
   ↓
5. Admin.login()
   ├─► Gets user by email
   └─► Verifies password
   ↓
6. Set session variables
   ↓
7. Redirect to dashboard
```

---

## 📦 Dependencies

### External Libraries (in assets/libs/)
- Bootstrap 5
- ApexCharts
- DataTables
- FullCalendar
- jQuery (via Bootstrap)
- Font Awesome Icons

### PHP Requirements
- PHP 7.4 or higher
- PDO Extension
- MySQL/MariaDB
- Session support

---

## 🔐 Security Layers

```
┌──────────────────────────────────────────────┐
│ 1. Input Layer                               │
│    • HTML5 validation                        │
│    • Required field checks                   │
└──────────────┬───────────────────────────────┘
               │
┌──────────────┴───────────────────────────────┐
│ 2. PHP Validation Layer                      │
│    • Email format validation                 │
│    • Password strength checks                │
│    • Type validation                         │
└──────────────┬───────────────────────────────┘
               │
┌──────────────┴───────────────────────────────┐
│ 3. Sanitization Layer                        │
│    • strip_tags()                            │
│    • htmlspecialchars()                      │
│    • trim()                                  │
└──────────────┬───────────────────────────────┘
               │
┌──────────────┴───────────────────────────────┐
│ 4. Database Layer                            │
│    • PDO Prepared Statements                 │
│    • Parameter binding                       │
│    • SQL injection prevention                │
└──────────────┬───────────────────────────────┘
               │
┌──────────────┴───────────────────────────────┐
│ 5. Authentication Layer                      │
│    • Password hashing (bcrypt)               │
│    • Session management                      │
│    • Login verification                      │
└──────────────────────────────────────────────┘
```

---

## 🚀 Migration Path

### Phase 1: Core Setup ✅
- [x] Create config folder
- [x] Create Database class with PDO
- [x] Create autoloader

### Phase 2: Models & Controllers ✅
- [x] Create Admin class
- [x] Create FormHandler class
- [x] Create ProfileController
- [x] Create AuthController

### Phase 3: Update Pages ✅
- [x] Update profile.php to use OOP
- [x] Update check_login.php
- [x] Update login.php to use OOP

### Phase 4: Future Migration (Optional)
- [ ] Migrate index.php
- [ ] Migrate other content pages
- [ ] Add more controllers
- [ ] Implement middleware pattern

---

## 📝 Usage Examples

### Using Admin Class:
```php
require_once 'config/autoload.php';
$db = Database::getInstance()->getConnection();
$admin = new Admin($db);

// Get admin by ID
$adminData = $admin->getById(1);

// Update profile
$admin->updateProfile(1, 'John', 'Doe', 'john@example.com');

// Update password
$admin->updatePassword(1, 'newPassword123');
```

### Using FormHandler:
```php
$formHandler = new FormHandler();

// Validate fields
$formHandler->validateRequired($_POST['email'], 'Email');
$formHandler->validateEmail($_POST['email']);

// Check for errors
if ($formHandler->hasErrors()) {
    echo $formHandler->getFirstError();
}
```

### Using Controllers:
```php
$profileController = new ProfileController($adminObj, $formHandler);
$profileController->loadAdminData($admin_id);
$profileController->processRequest($admin_id);

echo $profileController->getSuccessMessage();
```

---

## 🔧 Configuration

### Database Settings (config/Database.php):
```php
private $host = 'localhost';
private $db_name = 'ereal';
private $username = 'root';
private $password = '';
```

### Session Settings:
Sessions are managed in `check_login.php` and `AuthController.php`

---

## 📚 Additional Resources

- **OOP_STRUCTURE_README.md** - Detailed OOP documentation
- **include/database_setup.sql** - Database schema
- **assets/** - Frontend resources

---

**Version:** 2.0 (OOP Implementation)  
**Created:** November 24, 2025  
**Last Updated:** November 24, 2025

