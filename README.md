# 🐄 COW: Client-Oriented Web Development Project

**COW** is a web development learning project using PHP, MySQL, and jQuery.  
It is designed to be run locally with **XAMPP**, simulating client-server interaction, dynamic DOM manipulation, and data exchange using JSON and XML.

## ⚙️ How to Run (XAMPP)

1. Download and install [XAMPP](https://www.apachefriends.org/index.html).
2. Copy the project folder (`Session6-7-8`) into the `htdocs` directory inside your XAMPP installation.
   - Example: `C:\xampp\htdocs\Session6-7-8`
3. Start **Apache** and **MySQL** from the XAMPP Control Panel.
4. Open [phpMyAdmin](http://localhost/phpmyadmin) and set up the necessary database and tables.
5. In your browser, navigate to the following URL depending on the session:
   - `http://localhost/Session{session number}/html/home.html`
   - ex: `http://localhost/Session1/html/home.html`
## 📁 Folder Structure (Summary)

```
Session8/
├── html/
│   ├── home.html
│   ├── login.html
│   ├── reserve.html
│   ├── database.html
│   └── check_reservation.html
├── js/
│   ├── home_autocompleter.js
│   ├── login.js
│   ├── reserve_form_validation.js
│   ├── set_reservation_info.js
│   └── ...
├── php/
│   ├── get_database_content.php
│   ├── get_reservation_info.php
│   └── ...
├── css/
│   ├── home.css
│   └── reserve.css
├── packages/
│   ├── bootstrap-4.3.1_v2/
│   ├── jquery_3_4_0/
│   ├── jquery-ui-1.12.1/
│   └── scriptaculous-js-1.9.0/
├── assets/
├── includes/
└── reservations/
```

---

This project is intended for educational purposes in client-oriented web development using a local server environment.
