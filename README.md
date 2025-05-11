Live Demo--> https://urbanbuyy.netlify.app/

# Urban-Buy E-Commerce

A modern PHP/MySQL e-commerce website.  
**Features:** Product catalog, categories, cart, guest checkout, and more.

---

## 🚀 Getting Started

### 1. **Clone the Repository**

```bash
git clone https://github.com/yourusername/Urban-Buy-main.git
cd Urban-Buy-main
```
---

### 2. **Install XAMPP**

- Download and install [XAMPP](https://www.apachefriends.org/index.html) (includes Apache, PHP, and MySQL).
- Start **Apache** and **MySQL** from the XAMPP Control Panel.

---

### 3. **Set Up the Database**

#### a. **Open phpMyAdmin**

- Go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin) in your browser.

#### b. **Create a New Database**

- Click **New** in the sidebar.
- Name your database (e.g., `urbanbuy`), select **utf8_general_ci** collation, and click **Create**.

#### c. **Import the Database Structure & Sample Data**

- Click your new database in the sidebar.
- Go to the **Import** tab.
- Click **Choose File** and select the `database_setup.sql` file from this repo.
- Click **Go** to import.

---

### 4. **Configure Database Connection**

- Open `db_config.php` in the project root.
- Update the following lines if your database name/user/password are different:

```php
$servername = "localhost";
$username = "root";      // default XAMPP user
$password = "";          // default XAMPP password is empty
$dbname = "urbanbuy";    // use your chosen database name
```

---

### 5. **Run the Project**

- Place the `Urban-Buy-main` folder inside your XAMPP `htdocs` directory (usually `C:/xampp/htdocs/`).
- In your browser, go to:  
  [http://localhost/Urban-Buy-main/](http://localhost/Urban-Buy-main/)

---

### 6. **Default Users**

- The project supports guest checkout.
- You can register a new user or use the guest mode for testing.

---

## 🗄️ Sample Database Setup

A file named **`database_setup.sql`** is included in this repository.

### **How to Import:**

1. **Open phpMyAdmin** (`http://localhost/phpmyadmin`)
2. **Create a new database** (e.g., `urbanbuy`)
3. **Select your new database** in the sidebar
4. Go to the **Import** tab
5. Click **Choose File** and select `database_setup.sql` from the project folder
6. Click **Go** to import

This will create all tables and insert sample categories and products.

---

## 🛠️ Project Structure

```
Urban-Buy-main/
├── Cart.class.php
├── db_config.php
├── database_setup.sql
├── index.php
├── shop.php
├── cart.php
├── about.php
├── contact.php
├── style.css
├── script.js
└── ... (other files)
```

---

## 📝 Notes

- **Images:** Product/category images are loaded from URLs. You can update them in the database or code.
- **Security:** This project is for learning/demo purposes. For production, add input validation, password hashing, and other security best practices.
- **Customization:** You can add more products, categories, and features as needed.

---

## 💡 Troubleshooting

- **Blank Page/Error:** Check `db_config.php` settings and ensure your database is imported.
- **Port Issues:** If Apache/MySQL won't start, make sure ports 80/3306 are free or change them in XAMPP settings.
- **Database Import Fails:** Make sure you're importing into a new, empty database.

---

## 📦 Deployment

For local use, XAMPP is recommended.  
To deploy online, use a PHP/MySQL hosting provider and follow their upload/database import instructions.

---

## 🤝 Contributing

Pull requests are welcome! For major changes, please open an issue first.

---

## 📄 License

MIT

---

**Happy coding! 🚀**  
If you have any issues, open an issue on GitHub or ask for help!
