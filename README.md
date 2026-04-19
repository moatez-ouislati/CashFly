#  Cashlfy Web

> A modern financial management web application built with **Symfony 6.4**, **Bootstrap 5**, and **MySQL** — designed to help users track expenses, manage budgets and investments, and optimize cash flow efficiently.

---

##  Table of Contents

- [Introduction](#introduction)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Usage](#usage)
- [Contributing](#contributing)
- [Authors & Acknowledgment](#authors--acknowledgment)
- [FAQ](#faq)
- [Changelog](#changelog)
- [Contact](#contact)

---

##  Introduction

**Cashlfy Web** simplifies business finance management. It provides an intuitive interface for tracking income, expenses, cash flow, and investments, giving users clear insights into their financial health — all through a responsive web interface.

---

##  Prerequisites

Before getting started, make sure you have the following installed:

- PHP **8.2** or higher
- Symfony **6.4**
- [Composer](https://getcomposer.org/)
- Node.js & npm (for front-end assets)
- MySQL database
- Web server: Apache, Nginx, or Symfony Local Server

---

##  Installation

### 1. Clone the repository

```bash
git clone https://github.com/moatez-ouislati/cashlfy-web.git
cd cashlfy-web
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install front-end assets

```bash
npm install && npm run dev
```

### 4. Configure environment variables

```bash
cp .env .env.local
```

Edit `.env.local` and set your database credentials:

```env
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
```

### 5. Initialize the database

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

### 6. Start the application

```bash
symfony server:start
```

Visit [http://localhost:8000](http://localhost:8000) in your browser. 🎉

---

##  Usage

1. Access the web application via your browser.
2. Add, view, and manage financial records through the interface.
3. Monitor cash flow, generate reports, and export data as **CSV** or **PDF**.
4. Extend features by modifying Twig templates, Bootstrap components, or Symfony controllers.

---

##  Contributing

Contributions are welcome! To get started:

1. **Fork** the repository.
2. **Create** a new branch for your changes.
3. **Implement** your feature or bug fix.
4. **Write tests** (PHPUnit recommended).
5. **Run tests** to ensure they pass:
   ```bash
   php bin/phpunit
   ```
6. **Commit and push** your changes.
7. **Submit a pull request**.

---

##  Authors & Acknowledgment

Cashlfy Web was created and maintained by [**CashFly.org**](https://cashfly.org).

---

##  FAQ

**Q: What is Cashlfy Web?**  
A: A Symfony-based web application for business finance management.

**Q: How do I install Cashlfy Web?**  
A: Follow the [installation steps](#installation) above.

**Q: How do I run Cashlfy Web?**  
A: Start the Symfony server and access the application via your web browser.

**Q: How can I contribute to Cashlfy Web?**  
A: Follow the [contributing guidelines](#contributing) above.

---

## 📝 Changelog

| Version | Changes |
|---------|---------|
| `0.2.1` | Optimized database queries and improved UI responsiveness |
| `0.2.0` | Completed interface implementation and CRUD operations |
| `0.1.1` | Added MySQL database integration with Doctrine |
| `0.1.0` | Initial release with Symfony + Bootstrap UI |

---

##  Contact

Have questions or feedback about Cashlfy Web? Reach out to **[CashFly](https://cashfly.org)**.
