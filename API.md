# IIAQATAR API Documentation

This document describes the RESTful API endpoints available in the IIAQATAR application.

## Base URL

```
Production: https://iiaqatar.org/api/v1
Development: http://localhost:8000/api/v1
```

## Authentication

The API uses Laravel Sanctum for authentication. Most endpoints are public, but some require authentication.

### Obtaining an API Token

```http
POST /api/v1/auth/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password"
}
```

Response:
```json
{
    "token": "1|abc123...",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com"
    }
}
```

### Using the Token

Include the token in the Authorization header:

```
Authorization: Bearer YOUR_TOKEN_HERE
```

## Endpoints

### Blog Posts

#### List All Posts

```http
GET /api/v1/posts
```

Query Parameters:
- `page` (integer): Page number for pagination
- `per_page` (integer): Items per page (max 100)
- `category` (string): Filter by category slug
- `tag` (string): Filter by tag slug

Response:
```json
{
    "data": [
        {
            "id": 1,
            "title": "Welcome to IIAQATAR",
            "slug": "welcome-to-iiaqatar",
            "excerpt": "Welcome to the Islamic Institute...",
            "featured_image": "https://example.com/image.jpg",
            "published_at": "2024-01-01T00:00:00.000000Z",
            "views_count": 150,
            "author": {
                "id": 1,
                "name": "Admin User"
            },
            "category": {
                "id": 1,
                "name": "News",
                "slug": "news"
            },
            "tags": [
                {
                    "id": 1,
                    "name": "Testing",
                    "slug": "testing"
                }
            ]
        }
    ],
    "links": {
        "first": "...",
        "last": "...",
        "prev": null,
        "next": "..."
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 5,
        "per_page": 12,
        "to": 12,
        "total": 60
    }
}
```

#### Get Single Post

```http
GET /api/v1/posts/{slug}
```

Response:
```json
{
    "data": {
        "id": 1,
        "title": "Welcome to IIAQATAR",
        "slug": "welcome-to-iiaqatar",
        "content": "Full post content...",
        "excerpt": "Welcome to the Islamic Institute...",
        "featured_image": "https://example.com/image.jpg",
        "published_at": "2024-01-01T00:00:00.000000Z",
        "views_count": 150,
        "author": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@iiaqatar.org"
        },
        "category": {
            "id": 1,
            "name": "News",
            "slug": "news",
            "description": "Latest news and updates"
        },
        "tags": [
            {
                "id": 1,
                "name": "Testing",
                "slug": "testing"
            }
        ],
        "comments": [
            {
                "id": 1,
                "content": "Great post!",
                "user": {
                    "name": "John Doe"
                },
                "created_at": "2024-01-02T00:00:00.000000Z"
            }
        ]
    }
}
```

### Events

#### List All Events

```http
GET /api/v1/events
```

Query Parameters:
- `page` (integer): Page number
- `per_page` (integer): Items per page
- `upcoming` (boolean): Filter only upcoming events

Response:
```json
{
    "data": [
        {
            "id": 1,
            "title": "Annual QA Conference 2024",
            "slug": "annual-qa-conference-2024",
            "description": "Join us for our annual conference...",
            "location": "Doha, Qatar",
            "featured_image": "https://example.com/event.jpg",
            "start_date": "2024-03-15T09:00:00.000000Z",
            "end_date": "2024-03-17T17:00:00.000000Z",
            "registration_deadline": "2024-03-01T23:59:59.000000Z",
            "max_attendees": 200,
            "price": 500.00,
            "status": "published",
            "event_type": "offline"
        }
    ],
    "links": {...},
    "meta": {...}
}
```

#### Get Single Event

```http
GET /api/v1/events/{slug}
```

Response:
```json
{
    "data": {
        "id": 1,
        "title": "Annual QA Conference 2024",
        "slug": "annual-qa-conference-2024",
        "description": "Full event description...",
        "location": "Doha, Qatar",
        "featured_image": "https://example.com/event.jpg",
        "start_date": "2024-03-15T09:00:00.000000Z",
        "end_date": "2024-03-17T17:00:00.000000Z",
        "registration_deadline": "2024-03-01T23:59:59.000000Z",
        "max_attendees": 200,
        "registered_count": 45,
        "price": 500.00,
        "status": "published",
        "event_type": "offline"
    }
}
```

#### Register for Event (Authenticated)

```http
POST /api/v1/events/{event}/register
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
    "user_id": 1
}
```

Response:
```json
{
    "message": "Registration successful",
    "registration": {
        "id": 1,
        "event_id": 1,
        "user_id": 1,
        "status": "pending",
        "payment_status": "unpaid",
        "amount_paid": 500.00
    }
}
```

### Courses

#### List All Courses

```http
GET /api/v1/courses
```

Query Parameters:
- `page` (integer): Page number
- `per_page` (integer): Items per page
- `level` (string): Filter by level (beginner, intermediate, advanced)
- `category` (string): Filter by category slug
- `featured` (boolean): Filter only featured courses

Response:
```json
{
    "data": [
        {
            "id": 1,
            "title": "Fundamentals of Quality Assurance",
            "slug": "fundamentals-of-quality-assurance",
            "description": "Learn the core principles...",
            "featured_image": "https://example.com/course.jpg",
            "level": "beginner",
            "duration": 600,
            "price": 199.00,
            "discount_price": null,
            "is_featured": true,
            "instructor": {
                "id": 2,
                "name": "Instructor User"
            },
            "category": {
                "id": 2,
                "name": "Quality Assurance",
                "slug": "quality-assurance"
            },
            "lessons_count": 4
        }
    ],
    "links": {...},
    "meta": {...}
}
```

#### Get Single Course

```http
GET /api/v1/courses/{slug}
```

Response:
```json
{
    "data": {
        "id": 1,
        "title": "Fundamentals of Quality Assurance",
        "slug": "fundamentals-of-quality-assurance",
        "description": "Full course description...",
        "objectives": [
            "Understand QA principles",
            "Learn testing methodologies",
            "Master bug tracking"
        ],
        "requirements": [
            "Basic computer skills",
            "Interest in software testing"
        ],
        "featured_image": "https://example.com/course.jpg",
        "level": "beginner",
        "duration": 600,
        "price": 199.00,
        "discount_price": null,
        "max_students": null,
        "is_featured": true,
        "instructor": {
            "id": 2,
            "name": "Instructor User",
            "bio": "Experienced QA professional..."
        },
        "category": {
            "id": 2,
            "name": "Quality Assurance",
            "slug": "quality-assurance"
        },
        "lessons": [
            {
                "id": 1,
                "title": "Introduction to QA",
                "slug": "introduction-to-qa",
                "duration": 60,
                "order": 1,
                "is_free": true,
                "is_published": true
            },
            {
                "id": 2,
                "title": "Testing Fundamentals",
                "slug": "testing-fundamentals",
                "duration": 90,
                "order": 2,
                "is_free": false,
                "is_published": true
            }
        ]
    }
}
```

#### Enroll in Course (Authenticated)

```http
POST /api/v1/courses/{course}/enroll
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
    "user_id": 1
}
```

Response:
```json
{
    "message": "Enrollment successful",
    "enrollment": {
        "id": 1,
        "course_id": 1,
        "user_id": 1,
        "status": "active",
        "progress": 0,
        "payment_status": "unpaid",
        "amount_paid": 199.00
    }
}
```

## Error Responses

### 400 Bad Request

```json
{
    "message": "Validation error",
    "errors": {
        "email": [
            "The email field is required."
        ]
    }
}
```

### 401 Unauthorized

```json
{
    "message": "Unauthenticated."
}
```

### 403 Forbidden

```json
{
    "message": "This action is unauthorized."
}
```

### 404 Not Found

```json
{
    "message": "Resource not found."
}
```

### 422 Unprocessable Entity

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "field_name": [
            "Error message"
        ]
    }
}
```

### 429 Too Many Requests

```json
{
    "message": "Too many requests."
}
```

### 500 Internal Server Error

```json
{
    "message": "Server Error"
}
```

## Rate Limiting

API requests are limited to 60 requests per minute per user/IP address. Rate limit information is included in response headers:

```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1640000000
```

## Pagination

All list endpoints support pagination with the following parameters:

- `page`: Current page number (default: 1)
- `per_page`: Items per page (default: 12, max: 100)

Pagination information is included in the response:

```json
{
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 5,
        "per_page": 12,
        "to": 12,
        "total": 60
    },
    "links": {
        "first": "https://iiaqatar.org/api/v1/posts?page=1",
        "last": "https://iiaqatar.org/api/v1/posts?page=5",
        "prev": null,
        "next": "https://iiaqatar.org/api/v1/posts?page=2"
    }
}
```

## CORS

The API supports Cross-Origin Resource Sharing (CORS) for specified domains. Contact support to whitelist your domain.

## Webhooks

Stripe webhooks are configured at:

```
POST /stripe/webhook
```

This endpoint handles payment notifications for course enrollments and event registrations.

## Support

For API support and questions:
- Email: api@iiaqatar.org
- Documentation: https://github.com/Idris7umed/iiaqatar

## Changelog

### Version 1.0.0 (2024-12-04)
- Initial API release
- Blog posts endpoints
- Events endpoints
- Courses endpoints
- Authentication with Sanctum

---

Last Updated: December 2024
