# Wirasat Admin API Documentation

## Overview
This is a secure RESTful API for accessing all modules in the Wirasat CMS system. The API uses token-based authentication and provides GET endpoints for retrieving data from all database tables.

## Authentication

All API requests require a valid token to be sent in the request header.

### Token Header
```
X-API-Token: wirasat_api_token_2024_secure_key_change_this
```

**Important:** Change the API token in `config.php` before deploying to production.

### Alternative Methods
- Header: `X-API-Token`
- Query Parameter: `?token=your_token_here`

## Base URL
```
http://your-domain.com/adminapi/
```

## API Endpoints

### 1. Admin
Get admin user information.

**Endpoint:** `/admin` or `/adminapi/admin.php`

**Response:**
```json
{
  "success": true,
  "message": "Admins retrieved successfully",
  "data": [
    {
      "id": 1,
      "fname": "Admin",
      "lname": "User",
      "email": "admin@example.com",
      "created_at": "2024-01-01 00:00:00",
      "updated_at": "2024-01-01 00:00:00"
    }
  ],
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

**Get by ID:** `/admin?id=1`

---

### 2. Company Profile
Get company profile information.

**Endpoint:** `/company-profile` or `/company_profile`

**Query Parameters:**
- `id` - Get by ID
- `comp_id` - Get by company ID (default: 999999)

**Response:**
```json
{
  "success": true,
  "message": "Company profile retrieved successfully",
  "data": {
    "id": 1,
    "comp_id": "999999",
    "company_name": "Your Company",
    "company_logo": "http://domain.com/uploads/company/logo.jpg",
    "email_address": "info@company.com",
    ...
  }
}
```

---

### 3. About Us
Get about us content.

**Endpoint:** `/about-us` or `/about_us`

**Query Parameters:**
- `id` - Get by ID (optional, returns latest if not specified)

**Response:**
```json
{
  "success": true,
  "message": "About us data retrieved successfully",
  "data": {
    "id": 1,
    "about_us_paragraph": "<p>Content...</p>",
    "about_us_video": "http://domain.com/uploads/about/video.mp4",
    "status": 1
  }
}
```

---

### 4. CEO Message
Get CEO message content.

**Endpoint:** `/ceo-message` or `/ceo_message`

**Query Parameters:**
- `id` - Get by ID (optional, returns latest if not specified)

**Response:**
```json
{
  "success": true,
  "message": "CEO message retrieved successfully",
  "data": {
    "id": 1,
    "ceo_picture_1": "http://domain.com/uploads/ceo/picture1.jpg",
    "ceo_picture_2": "http://domain.com/uploads/ceo/picture2.jpg",
    "ceo_message_paragraph_1": "<p>Message...</p>",
    "status": 1
  }
}
```

---

### 5. Our Services
Get services list.

**Endpoint:** `/our-services` or `/our_services` or `/services`

**Query Parameters:**
- `id` - Get by ID
- `active_only` - Get only active services (1 or 0)

**Response:**
```json
{
  "success": true,
  "message": "Services retrieved successfully",
  "data": [
    {
      "id": 1,
      "service_title": "Service Name",
      "service_description": "Description...",
      "service_image": "http://domain.com/uploads/services/image.jpg",
      "display_order": 1,
      "status": 1
    }
  ]
}
```

---

### 6. Projects
Get projects list.

**Endpoint:** `/projects`

**Query Parameters:**
- `id` - Get by ID
- `comp_id` - Filter by company ID
- `active_only` - Get only active projects (1 or 0)

**Response:**
```json
{
  "success": true,
  "message": "Projects retrieved successfully",
  "data": [
    {
      "id": 1,
      "project_name": "Project Name",
      "project_map_thumbnail": "http://domain.com/uploads/projects/thumb.jpg",
      "documents": [
        {
          "id": 1,
          "document_name": "Document.pdf",
          "document_file": "http://domain.com/uploads/projects/doc.pdf"
        }
      ]
    }
  ]
}
```

---

### 7. Project Documents
Get project documents.

**Endpoint:** `/project-documents` or `/project_documents`

**Query Parameters:**
- `id` - Get by ID
- `project_id` - Filter by project ID

**Response:**
```json
{
  "success": true,
  "message": "Project documents retrieved successfully",
  "data": [
    {
      "id": 1,
      "project_id": 1,
      "document_name": "Document.pdf",
      "document_file": "http://domain.com/uploads/projects/doc.pdf"
    }
  ]
}
```

---

### 8. Gallery Pictures
Get gallery pictures.

**Endpoint:** `/gallery-pictures` or `/gallery_pictures` or `/pictures`

**Query Parameters:**
- `id` - Get by ID
- `active_only` - Get only active pictures (1 or 0)

**Response:**
```json
{
  "success": true,
  "message": "Gallery pictures retrieved successfully",
  "data": [
    {
      "id": 1,
      "picture_title": "Picture Title",
      "picture_file": "http://domain.com/uploads/gallery/pictures/image.jpg",
      "picture_thumbnail": "http://domain.com/uploads/gallery/pictures/thumb.jpg"
    }
  ]
}
```

---

### 9. Gallery Videos
Get gallery videos.

**Endpoint:** `/gallery-videos` or `/gallery_videos` or `/videos`

**Query Parameters:**
- `id` - Get by ID
- `active_only` - Get only active videos (1 or 0)

**Response:**
```json
{
  "success": true,
  "message": "Gallery videos retrieved successfully",
  "data": [
    {
      "id": 1,
      "video_title": "Video Title",
      "video_url": "https://youtube.com/watch?v=...",
      "video_thumbnail": "http://domain.com/uploads/gallery/videos/thumb.jpg"
    }
  ]
}
```

---

### 10. News Updates
Get news and updates.

**Endpoint:** `/news-updates` or `/news_updates` or `/news`

**Query Parameters:**
- `id` - Get by ID
- `active_only` - Get only active news (1 or 0)
- `limit` - Limit number of results

**Response:**
```json
{
  "success": true,
  "message": "News updates retrieved successfully",
  "data": [
    {
      "id": 1,
      "news_text": "<p>News content...</p>",
      "news_image": "http://domain.com/uploads/news/image.jpg",
      "news_date": "2024-01-01 00:00:00"
    }
  ]
}
```

---

### 11. Contact Messages
Get contact form messages.

**Endpoint:** `/contact-messages` or `/contact_messages` or `/messages` or `/contacts`

**Query Parameters:**
- `id` - Get by ID
- `unread_only` - Get only unread messages (1 or 0)
- `limit` - Limit number of results

**Response:**
```json
{
  "success": true,
  "message": "Contact messages retrieved successfully",
  "data": [
    {
      "id": 1,
      "full_name": "John Doe",
      "email_address": "john@example.com",
      "message_subject": "Subject",
      "message_body": "Message content...",
      "is_read": 0,
      "created_at": "2024-01-01 00:00:00"
    }
  ]
}
```

---

## Error Responses

### Unauthorized (401)
```json
{
  "success": false,
  "message": "Invalid API token",
  "error": "UNAUTHORIZED",
  "timestamp": "2024-01-01 12:00:00"
}
```

### Not Found (404)
```json
{
  "success": false,
  "message": "Resource not found",
  "data": null,
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

### Method Not Allowed (405)
```json
{
  "success": false,
  "message": "Method not allowed",
  "data": null,
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "Failed to retrieve data",
  "data": null,
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

---

## Usage Examples

### cURL Example
```bash
curl -X GET "http://your-domain.com/adminapi/projects" \
  -H "X-API-Token: wirasat_api_token_2024_secure_key_change_this"
```

### JavaScript (Fetch API)
```javascript
fetch('http://your-domain.com/adminapi/projects', {
  method: 'GET',
  headers: {
    'X-API-Token': 'wirasat_api_token_2024_secure_key_change_this'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

### PHP Example
```php
$url = 'http://your-domain.com/adminapi/projects';
$headers = [
    'X-API-Token: wirasat_api_token_2024_secure_key_change_this'
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
```

---

## Security Notes

1. **Change the API token** in `config.php` before production deployment
2. Use HTTPS in production
3. Implement rate limiting if needed
4. Regularly rotate API tokens
5. Monitor API access logs

---

## File Structure

```
adminapi/
├── config.php                 # Configuration and database
├── AuthMiddleware.php         # Token authentication
├── BaseApi.php                # Base API class
├── AdminApi.php               # Admin endpoint
├── CompanyProfileApi.php      # Company profile endpoint
├── AboutUsApi.php             # About us endpoint
├── CeoMessageApi.php          # CEO message endpoint
├── OurServicesApi.php         # Services endpoint
├── ProjectsApi.php            # Projects endpoint
├── ProjectDocumentsApi.php    # Project documents endpoint
├── GalleryPicturesApi.php     # Gallery pictures endpoint
├── GalleryVideosApi.php       # Gallery videos endpoint
├── NewsUpdatesApi.php         # News updates endpoint
├── ContactMessagesApi.php     # Contact messages endpoint
├── index.php                  # Main router
├── .htaccess                  # URL rewriting and security
└── README.md                  # This file
```

---

## Version
API Version: 1.0.0

---

## Support
For issues or questions, please contact the development team.

