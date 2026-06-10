# Sodakoh Pohon API Documentation

This application has been refactored into a microservices architecture. The API Gateway routes requests to three separate microservices:

## Base URL
`http://localhost` (or your configured domain)

## 1. User Service
Handles user authentication and management.
**Endpoint Prefix**: `/api/user/`

### `POST /api/user/login`
- **Description**: User login.
- **Body** (JSON):
  ```json
  {
      "email": "user@example.com",
      "password": "password123"
  }
  ```
- **Response**: JSON with user data and auth token.

### `POST /api/user/register`
- **Description**: Register a new user.
- **Body** (JSON):
  ```json
  {
      "name": "John Doe",
      "email": "john@example.com",
      "password": "password123",
      "phone": "08123456789"
  }
  ```
- **Response**: Success message.

---

## 2. Campaign Service
Handles campaign data, statistics, and submissions.
**Endpoint Prefix**: `/api/campaign/`

### `GET /api/campaign/list`
- **Description**: Get all campaigns.
- **Query Params**: `status` (optional), `limit` (optional)
- **Response**: Array of campaign objects.

### `GET /api/campaign/{id}`
- **Description**: Get campaign details by ID.
- **Response**: Single campaign object including gallery and benefits.

### `GET /api/campaign/stats`
- **Description**: Get global campaign statistics.
- **Response**: Object containing total trees planted, collected, etc.

### `GET /api/campaign/submissions`
- **Description**: Get all campaign submissions.

### `POST /api/campaign/submissions`
- **Description**: Create a new campaign submission.
- **Body** (JSON): Campaign submission data.

---

## 3. Transaction Service
Handles carts, donations, and checkouts.
**Endpoint Prefix**: `/api/transaction/`

### `GET /api/transaction/cart`
- **Description**: Get current user's cart summary and items.
- **Response**: Cart summary and array of items.

### `POST /api/transaction/cart`
- **Description**: Add item to cart.
- **Body** (JSON):
  ```json
  {
      "campaign_id": 1,
      "quantity": 5
  }
  ```

### `DELETE /api/transaction/cart`
- **Description**: Clear the cart.

### `GET /api/transaction/donations`
- **Description**: Get list of donations.
- **Query Params**: `status` (optional), `limit` (optional)

### `GET /api/transaction/donations/{id}`
- **Description**: Get specific donation details.

### `POST /api/transaction/checkout`
- **Description**: Process checkout.
- **Body** (JSON): Donation and donor details.

### `POST /api/transaction/confirm-payment`
- **Description**: Confirm payment for a donation.
- **Body** (JSON):
  ```json
  {
      "id": 1
  }
  ```
