# 🔗 URL Shortener API (Laravel)

A RESTful URL shortener API built with Laravel that allows users to create shortened URLs, track clicks, and view analytics.

---

## Features

-   Create shortened URLs
-   Redirect using hash-based URLs
-   Prevent duplicate URLs
-   Track clicks (IP, user agent, country)
-   Asynchronous click tracking using queues
-   Analytics dashboard data:

    -   Total clicks
    -   Unique visitors
    -   Time-series (clicks per day)

---

## Tech Stack

-   Laravel (PHP)
-   MySQL
-   Laravel Queues
-   Hashids (for URL encoding)
-   GeoIP (for country detection)

---

## API Endpoints

### 1. Get All URLs

`GET /url`

Returns paginated list of URLs.

---

### 2. Create Short URL

`POST /url`

#### Request:

```json
{
    "url": "https://example.com"
}
```

#### Response:

```json
{
    "message": "url created successfully",
    "data": {
        "original_url": "...",
        "short_url": "http://your-app/{hash}"
    }
}
```

---

### 3. Get Single URL

`GET /url/{hash}`

Returns details of a specific URL.

---

### 4. Redirect

`GET /{hash}`

Redirects to the original URL.

> Click tracking is processed asynchronously via queue.

---

### 5. Get Analytics

`GET /url/{hash}/analytics`

#### Response:

```json
{
    "id": 1,
    "original_url": "...",
    "short_url": "...",
    "total_clicks": 120,
    "unique_visitors": 80,
    "time_series": [
        { "date": "2026-05-01", "count": 10 },
        { "date": "2026-05-02", "count": 20 }
    ]
}
```

---

## ⚙️ How It Works

### URL Creation

-   Validates input URL
-   Checks for duplicates
-   Generates a unique hash using Hashids
-   Stores hash + original URL

---

### Redirect Flow

1. Decode hash → retrieve URL
2. Dispatch background job
3. Log click (IP, user agent, country)
4. Redirect user instantly

---

### Analytics

-   Aggregates clicks using SQL queries
-   Groups clicks by date
-   Counts unique visitors via distinct IPs

---

## Key Design Decisions

-   **Hash-based routing** instead of exposing database IDs
-   **Queue-based click tracking** for performance
-   **Service layer abstraction** for business logic
-   **Separate analytics endpoint** for scalability

---

## Future Improvements

-   Authentication (user-owned URLs)
-   Custom aliases
-   Rate limiting
-   Advanced analytics (device, browser, location breakdown)
-   Frontend dashboard (Vue/Inertia)

---

## ▶️ Running the Project

```bash
git clone <repo>
cd project

composer install
cp .env.example .env
php artisan key:generate

php artisan migrate
php artisan serve
```

---

## Queue Setup

```bash
php artisan queue:work
```

---
# url-shortner-api
