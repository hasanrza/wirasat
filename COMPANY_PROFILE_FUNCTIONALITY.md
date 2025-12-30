# Company Profile Functionality - Implementation Guide

## Overview
The Company Profile page (`company_profile.php`) is now fully functional with complete backend integration, form handling, and data persistence.

## Features Implemented

### 1. **Database Integration**
- Uses PDO with prepared statements for security
- Automatic creation/update logic (upsert functionality)
- Singleton database pattern for efficient connections

### 2. **Form Fields**
The form captures the following information:

#### Basic Information
- Company Name (required)
- Company Logo (image upload)
- Company Background Picture (image upload)
- Contact Us Footer Image (image upload)
- Company Address (rich text editor using Quill)

#### Social Media Links
- Facebook Link
- YouTube Link
- Twitter Link
- Instagram Link

#### Contact Information
- Website URL
- Email Address (required, validated)
- UAN (Universal Access Number)
- Mobile No. 1
- Mobile No. 2
- PTCL Number

#### Location
- Google Longitude
- Google Latitude

#### Settings
- Status (Active/Inactive toggle)

### 3. **File Upload Handling**
- Automatic file upload management
- Unique filename generation to prevent overwrites
- Old file deletion when new files are uploaded
- Image preview for uploaded files
- Stored in `uploads/company/` directory

### 4. **Form Validation**
- Required field validation (Company Name, Email)
- Email format validation
- Server-side sanitization of all inputs
- Error messages displayed to users

### 5. **User Feedback**
- Success messages on successful save
- Error messages for validation failures or save errors
- Bootstrap alert components with dismiss functionality

### 6. **Data Loading**
- Automatic loading of existing company profile
- Form pre-population with saved data
- Rich text editor loads saved content
- Image previews for uploaded files
- Default values for new profiles

## File Structure

### Frontend
- **company_profile.php** - Main page with form interface

### Backend Classes
- **classes/CompanyProfile.php** - Handles database operations
- **classes/FormHandler.php** - Form validation and sanitization
- **controllers/CompanyProfileController.php** - Business logic and request handling

### Database
- **Table**: `company_profile`
- **Location**: `include/cms_tables_setup.sql`

### File Uploads
- **Directory**: `uploads/company/`
- **Files**: Logos, backgrounds, footer images

## How It Works

### Page Load Flow
1. Check user login status
2. Initialize database connection and objects
3. Load existing company profile data (comp_id: 999999)
4. Pre-populate form fields with existing data
5. Display form with current values

### Form Submission Flow
1. User fills/updates form and clicks "Save"
2. Server receives POST request
3. Controller sanitizes and validates all inputs
4. File uploads are processed (if any)
5. Data is saved to database (create or update)
6. Success/error message is displayed
7. Form reloads with updated data

### File Upload Flow
1. User selects image file
2. On form submit, file is validated
3. Unique filename is generated (e.g., `company_logo_1638123456_abc123.jpg`)
4. Old file is deleted (if exists)
5. New file is moved to `uploads/company/`
6. Filename is saved in database
7. Image preview is displayed

## Usage Instructions

### First Time Setup
1. Ensure database table exists (run `include/cms_tables_setup.sql`)
2. Verify `uploads/company/` directory exists (auto-created by class)
3. Access page: `company_profile.php`
4. Fill in company details
5. Upload images
6. Click "Save"

### Updating Profile
1. Access `company_profile.php`
2. Form loads with current data
3. Modify desired fields
4. Upload new images (optional - keeps old if not changed)
5. Click "Save"
6. Success message confirms update

## Technical Details

### Security Features
- SQL injection prevention (prepared statements)
- XSS prevention (htmlspecialchars on output)
- Input sanitization (strip_tags, trim)
- File upload validation
- Session-based authentication

### Database Operations
- **Create**: First time profile creation
- **Update**: Subsequent profile modifications
- **Upsert**: Automatic detection of create vs update

### Image Management
- Unique filenames prevent conflicts
- Old files automatically deleted
- Supported formats: All image types (jpg, png, gif, etc.)
- File path stored in database (not full path)

## Default Values
- **comp_id**: 999999 (default company identifier)
- **status**: 1 (Active by default)
- **company_name**: "Your Company Name" (if fresh install)
- **email_address**: "info@company.com" (if fresh install)

## Error Handling
- Database connection errors
- File upload errors
- Validation errors
- Missing required fields
- Invalid email format
- Duplicate entries

## Rich Text Editor
- **Library**: Quill.js
- **Theme**: Snow (clean, modern interface)
- **Features**: Basic formatting, lists, links
- **Storage**: HTML format in database
- **Loading**: Automatic content restoration

## Validation Rules
1. **Company Name**: Required, cannot be empty
2. **Email Address**: Required, must be valid email format
3. **URLs**: Optional, but must be valid URL format if provided
4. **Status**: Boolean (Active = 1, Inactive = 0)
5. **Files**: Must be image format (validated by browser)

## API / Class Methods

### CompanyProfile Class
```php
getByCompId($compId)        // Get profile by company ID
getById($id)                // Get profile by database ID
save($data)                 // Create or update profile
handleFileUpload($file, $fieldName, $oldFile) // Process file uploads
validateEmail($email)       // Validate email format
```

### CompanyProfileController Class
```php
loadProfileData($compId)    // Load profile data
getProfileData()            // Get loaded data
processRequest()            // Handle form submission
getSuccessMessage()         // Get success message
getErrorMessage()           // Get error message
```

### FormHandler Class
```php
sanitize($data)             // Clean input data
validateRequired($value, $fieldName) // Check required fields
validateEmail($email)       // Validate email
addError($message)          // Add error message
addSuccess($message)        // Add success message
```

## Browser Compatibility
- Modern browsers (Chrome, Firefox, Edge, Safari)
- Requires JavaScript enabled (for Quill editor)
- Responsive design (Bootstrap 5)
- Mobile-friendly interface

## Future Enhancements (Optional)
- [ ] Image cropping before upload
- [ ] Multiple company profiles support
- [ ] Logo size validation
- [ ] Image optimization/compression
- [ ] Preview mode before saving
- [ ] Audit trail (who changed what and when)
- [ ] Backup/restore functionality
- [ ] Export profile as PDF
- [ ] Multi-language support

## Troubleshooting

### Images not uploading
- Check `uploads/company/` directory exists
- Verify directory permissions (write access)
- Check file size limits in php.ini
- Ensure `enctype="multipart/form-data"` in form

### Data not saving
- Check database connection
- Verify table structure matches SQL file
- Check PHP error logs
- Ensure all required fields are filled

### Form not loading data
- Verify database has data (comp_id: 999999)
- Check database connection
- Look for PHP errors in browser console
- Check if Database class is initialized

### Images not displaying
- Verify file path is correct
- Check file exists in uploads directory
- Ensure file permissions allow reading
- Check browser console for 404 errors

## Support
For issues or questions, check:
1. PHP error logs
2. Browser console
3. Database connection status
4. File permissions

---

**Implementation Date**: November 2025  
**Version**: 1.0  
**Status**: ✅ Fully Functional

