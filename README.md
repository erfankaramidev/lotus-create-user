# Lotus Create User

**Lotus Create User** is a WordPress plugin that provides a secure REST API endpoint for creating new users remotely.

It was originally developed as part of a private project and later open-sourced. While it provides a secure and simple REST API for creating users, it may not be suitable for all scenarios.

---

## Features

- Provides a secure REST API endpoint at `/wp-json/lotus/v1/create-user`
- Uses a **Bearer token** for authentication
- Auto-generates and manages the token in the WordPress options table
- Token can be viewed and reset in the WordPress admin settings
- WooCommerce compatible (creates WordPress users with billing information)
- Fully namespaced and PSR-4 autoloaded for clean code structure.

---

## Installation

Since this plugin is currently an open-source raw plugin, you'll need to install it manually.

### 1. Clone the repository

```bash
git clone https://github.com/erfankaramidev/lotus-create-user.git
```

### 2. Install Composer dependencies

```bash
cd lotus-create-user
composer install
```

### 3. Publish as a production package

```bash
zip -r lotus-create-user.zip .
```

Then upload the zip file to your WordPress site.

## Configuration

Once activated:

1. Go to **Settings → Lotus User Create API** in the WordPress admin.
2. Copy your **API Token** — it will look like a long random string.
3. If needed, click **Reset Token** to generate a new one.

## Usage

### Endpoint

```
POST /wp-json/lotus/v1/create-user
```

### Headers

```
Authorization: Bearer <token>
Content-Type: application/json
```

### Body

```json
{
  "username": "newuser",
  "first_name": "John",
  "last_name": "Doe",
  "phone": "09123456789"
}
```

### Response (201)

```json
{
  "user_id": 1,
  "message": "User created successfully.",
  "created": true
}
```

### Error (401 - Invalid Token)

```json
{
  "code": "rest_forbidden",
  "message": "Missing or invalid authentication token.",
  "data": {
    "status": 401
  }
}
```

---

## How it works

- On activation, the plugin generates a secure token and saves it to the database (`lotus_user_create_api_token`).
- The token is used to authenticate incoming REST API requests.
- When a request passes authentication, a new WordPress user is created with the `customer` role.
- Admins can view or reset the token from the plugin settings page.

---

## Uninstallation

When the plugin is uninstalled, it automatically removes:

- The stored API token (`lotus_user_create_api_token`) from the database.

---

## Technical Details

- **Version**: 1.1.0
- **PHP Requirement**: 7.4

---

## License

This plugin is distributed under the [GPL-2.0-or-later](https://www.gnu.org/licenses/gpl-2.0.html) license.
