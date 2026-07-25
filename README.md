# Lost and Found Item System — OOP Edition

A campus lost-and-found web app built with **PHP (OOP), a small custom
MVC-style framework, and MySQL**. Users can report lost items, report
found items, search, and view contact details. A dedicated **Admin**
role can moderate posts and fully manage user accounts (add/edit/delete).

## OOP Concepts Used

| Concept | Where |
|---|---|
| **Encapsulation** | `User`, `Item`, and `Database` keep their properties `private`, exposing them only through getter methods. `Database` also hides the PDO connection behind a Singleton. |
| **Inheritance** | `Student`, `Staff`, `Admin` all extend abstract `User`. `LostItem`, `FoundItem` both extend abstract `Item`. All controllers extend the base `Controller`. |
| **Polymorphism** | `$user->getRoleLabel()` and `$user->canManageUsers()` return different results depending on whether `$user` is a `Student`, `Staff`, or `Admin` — the calling code never checks `if student / if staff`. Same idea for `$item->getTypeLabel()` / `getBadgeClass()` on `LostItem` vs `FoundItem`. |
| **Abstraction** | `User` and `Item` are `abstract class`es — you can never do `new User(...)` directly, only through a concrete subclass. |
| **Factory Pattern** | `UserFactory::create($row)` reads `user_type` from the database and returns the correct subclass automatically. |
| **Small Framework** | A single front controller (`index.php`) + `Router` dispatches `?page=X&action=Y` to the right `Controller` method — similar in spirit to how Laravel/CodeIgniter route requests, just much smaller. |

## User Roles

- **Student / Staff** — register themselves, post lost/found items, search, view contact info.
- **Admin** — a separate actor (not just a flag). Can:
  - Delete inappropriate lost/found posts
  - **Add** new user accounts
  - **Edit** any user's details (name, email, phone, role, password)
  - **Delete** user accounts (blocked from deleting their own account while logged in, to avoid lock-out)

Admin accounts are not available on the public registration form — they're
created by an existing Admin through **Manage Users → Add New User**.

## Folder Structure

```
lost-found-oop/
├── config/
│   └── config.php            # DB credentials, BASE_URL, upload settings
├── core/                      # the "small framework" pieces
│   ├── Database.php           # Singleton PDO wrapper (encapsulation)
│   ├── Controller.php         # abstract base controller (inheritance)
│   ├── Router.php             # ?page=&action= dispatcher
│   └── Auth.php               # session/login helper
├── models/
│   ├── User.php                # abstract base
│   ├── Student.php / Staff.php / Admin.php   # concrete roles
│   ├── UserFactory.php          # builds the right subclass
│   ├── UserRepository.php       # all `users` table queries
│   ├── Item.php                 # abstract base
│   ├── LostItem.php / FoundItem.php
│   └── ItemRepository.php       # all lost_items/found_items queries
├── controllers/
│   ├── AuthController.php       # register/login/logout
│   ├── ItemController.php       # home/post/search/view
│   └── AdminController.php      # post moderation + user CRUD
├── views/
│   ├── layout/                  # shared header/footer
│   ├── auth/                    # register.php, login.php
│   ├── items/                   # home, post_item, search, view_item
│   └── admin/                   # dashboard, manage_users, user_form
├── assets/css, assets/js
├── uploads/                     # uploaded item images
├── sql/schema.sql               # schema + dummy data
└── index.php                    # front controller (single entry point)
```

## Setup Instructions (XAMPP / WAMP / MAMP)

1. Copy the `lost-found-oop` folder into your server's web root
   (e.g. `C:\xampp\htdocs\` on Windows, `/opt/lampp/htdocs/` on Linux/Mac).
2. Start Apache and MySQL.
3. Open phpMyAdmin and import `sql/schema.sql` — it creates the database,
   tables, and dummy data for you.
4. Check `config/config.php` and adjust `DB_USER` / `DB_PASS` if your MySQL
   setup differs from the XAMPP default (`root`, no password).
5. Make sure `uploads/` is writable by the web server.
6. Visit `http://localhost/lost-found-oop/index.php`.

## Demo Logins

| Role | Email | Password |
|---|---|---|
| Admin | admin@lostfound.edu.lk | password123 |
| Student | nimal.perera@student.edu.lk | password123 |
| Staff | ruwan.fernando@staff.edu.lk | password123 |

## Validation

- **Client-side (JavaScript, `assets/js/validation.js`):** instant feedback —
  required fields, email format, phone must be exactly 10 digits, password
  length/match, image type & size.
- **Server-side (PHP, inside each Controller):** the same checks are repeated
  server-side since JavaScript can be disabled — this is the actual security
  boundary. Passwords are hashed with `password_hash()`; all queries use
  PDO prepared statements to prevent SQL injection.

## Ideas for Further Improvement

- Add an `AbstractRepository` base class if you want to practice inheritance
  further (both `UserRepository` and `ItemRepository` share a similar shape).
- Add pagination once there are many items.
- Let students/staff mark their own posts "resolved".
- Add simple keyword-matching between lost and found posts.
- Add CSRF tokens to all forms for extra security practice.
