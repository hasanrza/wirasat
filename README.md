# Wirasat Admin API - Complete Documentation

## Table of Contents
1. [Overview](#overview)
2. [Authentication](#authentication)
3. [Base URL & Endpoints](#base-url--endpoints)
4. [Request Format](#request-format)
5. [Response Format](#response-format)
6. [Error Handling](#error-handling)
7. [API Endpoints](#api-endpoints)
   - [Admin](#1-admin)
   - [Company Profile](#2-company-profile)
   - [About Us](#3-about-us)
   - [CEO Message](#4-ceo-message)
   - [Our Services](#5-our-services)
   - [Projects](#6-projects)
   - [Project Documents](#7-project-documents)
   - [Gallery Pictures](#8-gallery-pictures)
   - [Gallery Videos](#9-gallery-videos)
   - [News Updates](#10-news-updates)
   - [Contact Messages](#11-contact-messages)
8. [Query Parameters Reference](#query-parameters-reference)
9. [File Path Handling](#file-path-handling)
10. [Rate Limiting & Best Practices](#rate-limiting--best-practices)
11. [Troubleshooting](#troubleshooting)

---

## Overview

The Wirasat Admin API is a secure RESTful API that provides read-only access (GET requests) to all modules in the Wirasat CMS system. The API uses token-based authentication and returns data in JSON format.

### Key Features
- **Token-based Authentication**: Secure access using API tokens
- **Read-Only Operations**: Only GET requests are supported
- **JSON Responses**: All responses are in JSON format
- **Automatic File URL Conversion**: File paths are automatically converted to full URLs
- **CORS Support**: Cross-origin requests are supported
- **Error Handling**: Comprehensive error responses with status codes

### API Version
Current Version: **1.0.0**

---

## Authentication

All API requests require authentication using an API token.

### Token Location
The API token is defined in `config.php`:
```php
define('API_TOKEN', 'wirasat_api_token_2024_secure_key_change_this');
```

**⚠️ IMPORTANT:** Change this token to a secure random string before deploying to production!

### How to Send Token

#### Method 1: HTTP Header (Recommended)
```
X-API-Token: wirasat_api_token_2024_secure_key_change_this
```

#### Method 2: Query Parameter
```
?token=wirasat_api_token_2024_secure_key_change_this
```

### Authentication Example

**cURL:**
```bash
curl -X GET "http://your-domain.com/adminapi/projects" \
  -H "X-API-Token: wirasat_api_token_2024_secure_key_change_this"
```

**JavaScript:**
```javascript
fetch('http://your-domain.com/adminapi/projects', {
  headers: {
    'X-API-Token': 'wirasat_api_token_2024_secure_key_change_this'
  }
})
```

**PHP:**
```php
$headers = [
    'X-API-Token: wirasat_api_token_2024_secure_key_change_this'
];
```

### Authentication Errors

If authentication fails, you'll receive a **401 Unauthorized** response:
```json
{
  "success": false,
  "message": "Invalid API token",
  "error": "UNAUTHORIZED",
  "timestamp": "2024-01-01 12:00:00"
}
```

---

## Base URL & Endpoints

### Base URL Structure
```
http://your-domain.com/adminapi/
```

### Endpoint Formats
The API supports multiple endpoint formats for flexibility:

1. **Direct File Access:**
   ```
   /adminapi/ProjectsApi.php
   /adminapi/CompanyProfileApi.php
   ```

2. **Router Access (Recommended):**
   ```
   /adminapi/projects
   /adminapi/company-profile
   /adminapi/our-services
   ```

3. **Alternative Formats:**
   ```
   /adminapi/projects (same as above)
   /adminapi/project-documents
   /adminapi/gallery-pictures
   /adminapi/gallery-videos
   ```

### Available Endpoints

| Endpoint | Alternative Names | Description |
|----------|------------------|-------------|
| `admin` | - | Admin users |
| `company-profile` | `company_profile` | Company profile |
| `about-us` | `about_us` | About us content |
| `ceo-message` | `ceo_message` | CEO message |
| `our-services` | `our_services`, `services` | Services list |
| `projects` | - | Projects |
| `project-documents` | `project_documents` | Project documents |
| `gallery-pictures` | `gallery_pictures`, `pictures` | Gallery pictures |
| `gallery-videos` | `gallery_videos`, `videos` | Gallery videos |
| `news-updates` | `news_updates`, `news` | News updates |
| `contact-messages` | `contact_messages`, `messages`, `contacts` | Contact messages |

---

## Request Format

### HTTP Method
**Only GET requests are allowed.** All other methods (POST, PUT, DELETE, etc.) will return a **405 Method Not Allowed** error.

### Request Structure
```
GET /adminapi/{endpoint}?{query_parameters}
```

### Query Parameters
Query parameters are optional and vary by endpoint. They are appended to the URL after a `?` and separated by `&`.

**Example:**
```
/adminapi/projects?id=1&active_only=1
/adminapi/news-updates?active_only=1&limit=10
```

---

## Response Format

### Success Response Structure
```json
{
  "success": true,
  "message": "Data retrieved successfully",
  "data": {
    // Response data here
  },
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

### Error Response Structure
```json
{
  "success": false,
  "message": "Error message here",
  "data": null,
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

### Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `success` | boolean | Indicates if the request was successful |
| `message` | string | Human-readable message |
| `data` | object/array/null | The actual data (null on error) |
| `timestamp` | string | Response timestamp (UTC) |
| `version` | string | API version number |

---

## Error Handling

### HTTP Status Codes

| Code | Meaning | Description |
|------|---------|-------------|
| 200 | OK | Request successful |
| 401 | Unauthorized | Invalid or missing API token |
| 404 | Not Found | Resource not found |
| 405 | Method Not Allowed | Request method not supported |
| 500 | Internal Server Error | Server error occurred |

### Error Response Examples

#### 401 Unauthorized
```json
{
  "success": false,
  "message": "Invalid API token",
  "error": "UNAUTHORIZED",
  "timestamp": "2024-01-01 12:00:00"
}
```

#### 404 Not Found
```json
{
  "success": false,
  "message": "Project not found",
  "data": null,
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

#### 405 Method Not Allowed
```json
{
  "success": false,
  "message": "Method not allowed",
  "data": null,
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

#### 500 Internal Server Error
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

## API Endpoints

### 1. Admin

Get admin user information.

#### Endpoint
```
GET /adminapi/admin
```

#### Query Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | No | Get specific admin by ID |

#### Request Examples

**Get All Admins:**
```
GET /adminapi/admin
```

**Get Admin by ID:**
```
GET /adminapi/admin?id=1
```

#### Response Examples

**All Admins:**
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
    },
    {
      "id": 2,
      "fname": "John",
      "lname": "Doe",
      "email": "john@example.com",
      "created_at": "2024-01-02 00:00:00",
      "updated_at": "2024-01-02 00:00:00"
    }
  ],
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

**Single Admin:**
```json
{
  "success": true,
  "message": "Admin retrieved successfully",
  "data": {
    "id": 1,
    "fname": "Admin",
    "lname": "User",
    "email": "admin@example.com",
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  },
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

**Note:** Password fields are automatically excluded from responses for security.

---

### 2. Company Profile

Get company profile information.

#### Endpoint
```
GET /adminapi/company-profile
GET /adminapi/company_profile
```

#### Query Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | No | Get by database ID |
| `comp_id` | string | No | Get by company ID (default: '999999') |

#### Request Examples

**Get All Company Profiles:**
```
GET /adminapi/company-profile
```

**Get by ID:**
```
GET /adminapi/company-profile?id=1
```

**Get by Company ID:**
```
GET /adminapi/company-profile?comp_id=999999
```

#### Response Example
```json
{
  "success": true,
  "message": "Company profile retrieved successfully",
  "data": {
    "id": 1,
    "comp_id": "999999",
    "company_name": "Wirasat Company",
    "company_logo": "http://your-domain.com/uploads/company/logo.jpg",
    "company_background": "http://your-domain.com/uploads/company/bg.jpg",
    "footer_image": "http://your-domain.com/uploads/company/footer.jpg",
    "company_address": "123 Main Street, City, Country",
    "facebook_link": "https://facebook.com/company",
    "youtube_link": "https://youtube.com/company",
    "twitter_link": "https://twitter.com/company",
    "instagram_link": "https://instagram.com/company",
    "website_url": "https://company.com",
    "email_address": "info@company.com",
    "uan": "123456789",
    "mobile_1": "+1234567890",
    "mobile_2": "+1234567891",
    "ptcl_number": "+1234567892",
    "longitude": "74.3587",
    "latitude": "31.5204",
    "status": 1,
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  },
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

**Note:** Image fields (company_logo, company_background, footer_image) are automatically converted to full URLs.

---

### 3. About Us

Get about us content.

#### Endpoint
```
GET /adminapi/about-us
GET /adminapi/about_us
```

#### Query Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | No | Get by ID (if not provided, returns latest) |

#### Request Examples

**Get Latest About Us:**
```
GET /adminapi/about-us
```

**Get by ID:**
```
GET /adminapi/about-us?id=1
```

#### Response Example
```json
{
  "success": true,
  "message": "About us data retrieved successfully",
  "data": {
    "id": 1,
    "about_us_paragraph": "<p>Welcome to our company. We are dedicated to providing excellent services to our clients.</p>",
    "about_us_video": "http://your-domain.com/uploads/about/video.mp4",
    "status": 1,
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  },
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

**Note:** If no `id` is provided, the API returns the most recent record.

---

### 4. CEO Message

Get CEO message content.

#### Endpoint
```
GET /adminapi/ceo-message
GET /adminapi/ceo_message
```

#### Query Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | No | Get by ID (if not provided, returns latest) |

#### Request Examples

**Get Latest CEO Message:**
```
GET /adminapi/ceo-message
```

**Get by ID:**
```
GET /adminapi/ceo-message?id=1
```

#### Response Example
```json
{
  "success": true,
  "message": "CEO message retrieved successfully",
  "data": {
    "id": 1,
    "ceo_picture_1": "http://your-domain.com/uploads/ceo/picture1.jpg",
    "ceo_picture_2": "http://your-domain.com/uploads/ceo/picture2.jpg",
    "ceo_message_paragraph_1": "<p>Welcome message from our CEO...</p>",
    "ceo_message_paragraph_2": "<p>Additional message content...</p>",
    "status": 1,
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  },
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

---

### 5. Our Services

Get services list.

#### Endpoint
```
GET /adminapi/our-services
GET /adminapi/our_services
GET /adminapi/services
```

#### Query Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | No | Get specific service by ID |
| `active_only` | integer | No | Filter only active services (1 = yes, 0 = no, default: 0) |

#### Request Examples

**Get All Services:**
```
GET /adminapi/our-services
```

**Get Active Services Only:**
```
GET /adminapi/our-services?active_only=1
```

**Get Service by ID:**
```
GET /adminapi/our-services?id=1
```

#### Response Example
```json
{
  "success": true,
  "message": "Services retrieved successfully",
  "data": [
    {
      "id": 1,
      "service_title": "Web Development",
      "service_description": "<p>Professional web development services...</p>",
      "service_icon": "fa-code",
      "service_image": "http://your-domain.com/uploads/services/web-dev.jpg",
      "display_order": 1,
      "status": 1,
      "created_at": "2024-01-01 00:00:00",
      "updated_at": "2024-01-01 00:00:00"
    },
    {
      "id": 2,
      "service_title": "Mobile App Development",
      "service_description": "<p>Mobile application development...</p>",
      "service_icon": "fa-mobile",
      "service_image": "http://your-domain.com/uploads/services/mobile-app.jpg",
      "display_order": 2,
      "status": 1,
      "created_at": "2024-01-01 00:00:00",
      "updated_at": "2024-01-01 00:00:00"
    }
  ],
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

**Note:** Results are ordered by `display_order` (ascending), then by `id` (descending).

---

### 6. Projects

Get projects list with associated documents.

#### Endpoint
```
GET /adminapi/projects
```

#### Query Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | No | Get specific project by ID |
| `comp_id` | string | No | Filter by company ID |
| `active_only` | integer | No | Filter only active projects (1 = yes, 0 = no, default: 0) |

#### Request Examples

**Get All Projects:**
```
GET /adminapi/projects
```

**Get Active Projects Only:**
```
GET /adminapi/projects?active_only=1
```

**Get Projects by Company ID:**
```
GET /adminapi/projects?comp_id=999999
```

**Get Project by ID:**
```
GET /adminapi/projects?id=1
```

#### Response Example
```json
{
  "success": true,
  "message": "Projects retrieved successfully",
  "data": [
    {
      "id": 1,
      "comp_id": "999999",
      "project_id": "PROJ001",
      "project_name": "Residential Complex",
      "project_map_thumbnail": "http://your-domain.com/uploads/projects/map-thumb.jpg",
      "project_map_full": "http://your-domain.com/uploads/projects/map-full.jpg",
      "project_payment_plan": "http://your-domain.com/uploads/projects/payment-plan.pdf",
      "project_amenities": "<p>Swimming pool, Gym, Park, etc.</p>",
      "project_amenities_image": "http://your-domain.com/uploads/projects/amenities.jpg",
      "status": 1,
      "created_at": "2024-01-01 00:00:00",
      "updated_at": "2024-01-01 00:00:00",
      "documents": [
        {
          "id": 1,
          "project_id": 1,
          "doc_id": "DOC001",
          "document_thumbnail": "http://your-domain.com/uploads/projects/doc-thumb.jpg",
          "document_name": "Brochure.pdf",
          "document_file": "http://your-domain.com/uploads/projects/brochure.pdf",
          "display_order": 1,
          "created_at": "2024-01-01 00:00:00",
          "updated_at": "2024-01-01 00:00:00"
        }
      ]
    }
  ],
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

**Note:** Projects automatically include their associated documents in the response.

---

### 7. Project Documents

Get project documents.

#### Endpoint
```
GET /adminapi/project-documents
GET /adminapi/project_documents
```

#### Query Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | No | Get specific document by ID |
| `project_id` | integer | No | Filter documents by project ID |

#### Request Examples

**Get All Documents:**
```
GET /adminapi/project-documents
```

**Get Documents for Specific Project:**
```
GET /adminapi/project-documents?project_id=1
```

**Get Document by ID:**
```
GET /adminapi/project-documents?id=1
```

#### Response Example
```json
{
  "success": true,
  "message": "Project documents retrieved successfully",
  "data": [
    {
      "id": 1,
      "project_id": 1,
      "doc_id": "DOC001",
      "document_thumbnail": "http://your-domain.com/uploads/projects/doc-thumb.jpg",
      "document_name": "Project Brochure",
      "document_file": "http://your-domain.com/uploads/projects/brochure.pdf",
      "display_order": 1,
      "created_at": "2024-01-01 00:00:00",
      "updated_at": "2024-01-01 00:00:00"
    }
  ],
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

**Note:** Results are ordered by `display_order` (ascending), then by `id` (ascending).

---

### 8. Gallery Pictures

Get gallery pictures.

#### Endpoint
```
GET /adminapi/gallery-pictures
GET /adminapi/gallery_pictures
GET /adminapi/pictures
```

#### Query Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | No | Get specific picture by ID |
| `active_only` | integer | No | Filter only active pictures (1 = yes, 0 = no, default: 0) |

#### Request Examples

**Get All Pictures:**
```
GET /adminapi/gallery-pictures
```

**Get Active Pictures Only:**
```
GET /adminapi/gallery-pictures?active_only=1
```

**Get Picture by ID:**
```
GET /adminapi/gallery-pictures?id=1
```

#### Response Example
```json
{
  "success": true,
  "message": "Gallery pictures retrieved successfully",
  "data": [
    {
      "id": 1,
      "picture_id": "pic_1234567890_1234",
      "picture_title": "Office Building",
      "picture_description": "Our main office building",
      "picture_file": "http://your-domain.com/uploads/gallery/pictures/office.jpg",
      "picture_thumbnail": "http://your-domain.com/uploads/gallery/pictures/office-thumb.jpg",
      "display_order": 1,
      "status": 1,
      "created_at": "2024-01-01 00:00:00",
      "updated_at": "2024-01-01 00:00:00"
    }
  ],
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

**Note:** Results are ordered by `display_order` (ascending), then by `created_at` (descending).

---

### 9. Gallery Videos

Get gallery videos.

#### Endpoint
```
GET /adminapi/gallery-videos
GET /adminapi/gallery_videos
GET /adminapi/videos
```

#### Query Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | No | Get specific video by ID |
| `active_only` | integer | No | Filter only active videos (1 = yes, 0 = no, default: 0) |

#### Request Examples

**Get All Videos:**
```
GET /adminapi/gallery-videos
```

**Get Active Videos Only:**
```
GET /adminapi/gallery-videos?active_only=1
```

**Get Video by ID:**
```
GET /adminapi/gallery-videos?id=1
```

#### Response Example
```json
{
  "success": true,
  "message": "Gallery videos retrieved successfully",
  "data": [
    {
      "id": 1,
      "video_id": "vid_1234567890_1234",
      "video_title": "Company Introduction",
      "video_description": "Introduction to our company",
      "video_url": "https://youtube.com/watch?v=abc123",
      "video_thumbnail": "http://your-domain.com/uploads/gallery/videos/thumb.jpg",
      "video_embed_code": "<iframe src='...'></iframe>",
      "display_order": 1,
      "status": 1,
      "created_at": "2024-01-01 00:00:00",
      "updated_at": "2024-01-01 00:00:00"
    }
  ],
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

---

### 10. News Updates

Get news and updates.

#### Endpoint
```
GET /adminapi/news-updates
GET /adminapi/news_updates
GET /adminapi/news
```

#### Query Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | No | Get specific news by ID |
| `active_only` | integer | No | Filter only active news (1 = yes, 0 = no, default: 0) |
| `limit` | integer | No | Limit number of results (e.g., 10, 20, 50) |

#### Request Examples

**Get All News:**
```
GET /adminapi/news-updates
```

**Get Active News Only:**
```
GET /adminapi/news-updates?active_only=1
```

**Get Latest 10 News:**
```
GET /adminapi/news-updates?active_only=1&limit=10
```

**Get News by ID:**
```
GET /adminapi/news-updates?id=1
```

#### Response Example
```json
{
  "success": true,
  "message": "News updates retrieved successfully",
  "data": [
    {
      "id": 1,
      "news_id": "news_1234567890_1234",
      "news_text": "<p>Latest company news and updates...</p>",
      "news_image": "http://your-domain.com/uploads/news/news-image.jpg",
      "news_video": "http://your-domain.com/uploads/news/news-video.mp4",
      "youtube_link": "https://youtube.com/watch?v=abc123",
      "news_date": "2024-01-01 00:00:00",
      "status": 1,
      "created_at": "2024-01-01 00:00:00",
      "updated_at": "2024-01-01 00:00:00"
    }
  ],
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

**Note:** Results are ordered by `news_date` (descending), then by `created_at` (descending).

---

### 11. Contact Messages

Get contact form messages.

#### Endpoint
```
GET /adminapi/contact-messages
GET /adminapi/contact_messages
GET /adminapi/messages
GET /adminapi/contacts
```

#### Query Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | No | Get specific message by ID |
| `unread_only` | integer | No | Filter only unread messages (1 = yes, 0 = no, default: 0) |
| `limit` | integer | No | Limit number of results (e.g., 10, 20, 50) |

#### Request Examples

**Get All Messages:**
```
GET /adminapi/contact-messages
```

**Get Unread Messages Only:**
```
GET /adminapi/contact-messages?unread_only=1
```

**Get Latest 20 Messages:**
```
GET /adminapi/contact-messages?limit=20
```

**Get Message by ID:**
```
GET /adminapi/contact-messages?id=1
```

#### Response Example
```json
{
  "success": true,
  "message": "Contact messages retrieved successfully",
  "data": [
    {
      "id": 1,
      "contact_id": "contact_1234567890_1234",
      "full_name": "John Doe",
      "email_address": "john@example.com",
      "phone_number": "+1234567890",
      "message_subject": "Inquiry about services",
      "message_body": "I would like to know more about your services...",
      "is_read": 0,
      "created_at": "2024-01-01 00:00:00",
      "updated_at": "2024-01-01 00:00:00"
    }
  ],
  "timestamp": "2024-01-01 12:00:00",
  "version": "1.0.0"
}
```

**Note:** Results are ordered by `created_at` (descending).

---

## Query Parameters Reference

### Common Parameters

| Parameter | Type | Values | Description |
|----------|------|--------|-------------|
| `id` | integer | Any positive integer | Get specific record by database ID |
| `active_only` | integer | 0 or 1 | Filter only active records (1 = active only) |
| `limit` | integer | Any positive integer | Limit number of results returned |
| `unread_only` | integer | 0 or 1 | Filter only unread records (1 = unread only) |

### Endpoint-Specific Parameters

| Endpoint | Parameter | Type | Description |
|----------|----------|------|-------------|
| `company-profile` | `comp_id` | string | Filter by company ID |
| `projects` | `comp_id` | string | Filter by company ID |
| `project-documents` | `project_id` | integer | Filter by project ID |

### Parameter Usage Tips

1. **Multiple Parameters:** Combine parameters with `&`:
   ```
   /adminapi/news-updates?active_only=1&limit=10
   ```

2. **Boolean Values:** Use `1` for true, `0` for false (or omit for false)

3. **Integer Values:** Must be valid positive integers

4. **String Values:** No quotes needed, URL-encoded automatically

---

## File Path Handling

### Automatic URL Conversion

The API automatically converts relative file paths to full URLs. For example:

**Database Storage:**
```
uploads/company/logo.jpg
```

**API Response:**
```json
{
  "company_logo": "http://your-domain.com/uploads/company/logo.jpg"
}
```

### File Fields by Endpoint

| Endpoint | File Fields |
|----------|-------------|
| `company-profile` | `company_logo`, `company_background`, `footer_image` |
| `about-us` | `about_us_video` |
| `ceo-message` | `ceo_picture_1`, `ceo_picture_2` |
| `our-services` | `service_image` |
| `projects` | `project_map_thumbnail`, `project_map_full`, `project_payment_plan`, `project_amenities_image` |
| `project-documents` | `document_thumbnail`, `document_file` |
| `gallery-pictures` | `picture_file`, `picture_thumbnail` |
| `gallery-videos` | `video_thumbnail` |
| `news-updates` | `news_image`, `news_video` |

### Base URL Detection

The API automatically detects the base URL from the request:
- Protocol: `http` or `https` (based on request)
- Host: From `HTTP_HOST` header
- Path: Automatically calculated from script location

---

## Rate Limiting & Best Practices

### Rate Limiting

Currently, there is no built-in rate limiting. Consider implementing:
- Maximum requests per minute/hour
- IP-based throttling
- Token-based quotas

### Best Practices

1. **Cache Responses:** Cache API responses on the client side to reduce server load
2. **Use Specific Endpoints:** Use specific IDs when possible instead of fetching all records
3. **Limit Results:** Use `limit` parameter to reduce data transfer
4. **Filter Active Records:** Use `active_only=1` to get only published content
5. **Error Handling:** Always check the `success` field before using data
6. **Token Security:** Keep your API token secure and rotate it regularly
7. **HTTPS:** Always use HTTPS in production environments

### Caching Example

```javascript
// Cache for 5 minutes
const cache = new Map();
const CACHE_DURATION = 5 * 60 * 1000; // 5 minutes

async function getCachedData(endpoint) {
  const cacheKey = endpoint;
  const cached = cache.get(cacheKey);
  
  if (cached && Date.now() - cached.timestamp < CACHE_DURATION) {
    return cached.data;
  }
  
  const response = await fetch(endpoint, {
    headers: { 'X-API-Token': API_TOKEN }
  });
  const data = await response.json();
  
  cache.set(cacheKey, {
    data: data,
    timestamp: Date.now()
  });
  
  return data;
}
```

---

## Troubleshooting

### Common Issues

#### 1. 401 Unauthorized Error

**Problem:** Invalid or missing API token

**Solutions:**
- Check that the token is correctly set in the request header
- Verify the token matches the one in `config.php`
- Ensure the header name is exactly `X-API-Token`

#### 2. 404 Not Found Error

**Problem:** Endpoint or resource not found

**Solutions:**
- Verify the endpoint URL is correct
- Check that the resource ID exists in the database
- Ensure the endpoint name matches the available endpoints

#### 3. 405 Method Not Allowed

**Problem:** Using unsupported HTTP method

**Solutions:**
- Only GET requests are supported
- Change POST/PUT/DELETE to GET

#### 4. Empty Data Response

**Problem:** No records found in database

**Solutions:**
- Check database for existing records
- Verify table names match
- Check database connection settings

#### 5. File URLs Not Working

**Problem:** File paths not converting to URLs correctly

**Solutions:**
- Verify file paths in database are relative (e.g., `uploads/...`)
- Check that files exist in the uploads directory
- Verify base URL detection is working

#### 6. CORS Issues

**Problem:** Cross-origin requests blocked

**Solutions:**
- Check CORS headers in `AuthMiddleware.php`
- Verify `API_ALLOWED_ORIGINS` setting in `config.php`
- Ensure server allows OPTIONS requests

### Debug Mode

To enable debug mode, modify `config.php`:

```php
// Enable error display (development only)
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

**⚠️ Warning:** Never enable debug mode in production!

### Testing Endpoints

Use these tools to test the API:

1. **cURL:**
   ```bash
   curl -v -H "X-API-Token: your_token" http://your-domain.com/adminapi/projects
   ```

2. **Postman:**
   - Create GET request
   - Add header: `X-API-Token: your_token`
   - Send request

3. **Browser Console:**
   ```javascript
   fetch('http://your-domain.com/adminapi/projects', {
     headers: { 'X-API-Token': 'your_token' }
   })
   .then(r => r.json())
   .then(console.log);
   ```

---

## Additional Resources

- **README.md** - Quick start guide
- **API_EXAMPLES.md** - Code examples in multiple languages
- **config.php** - API configuration file

---

## Support

For issues, questions, or feature requests, please contact the development team.

**API Version:** 1.0.0  
**Last Updated:** 2024

---

## License

This API is part of the Wirasat CMS system.

