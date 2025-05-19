# 📝 Si j’étais une fleur

“Si j’étais une fleur” is a web application developed during my 5-week internship. It is a point-of-sale (POS) software with a dashboard to manage sales, export Excel files, and automatically send invoices to clients.

## 🎯 Purpose

The main goal of this application is to simplify accounting tasks, improve productivity through automation, and centralize key business data in an intuitive interface.

## 🛠️ Main Features

- Create tickets, invoices, clients, and items.
- Export Excel files (clients list, sales over a specific period, etc.).
- Export all invoices for accounting purposes.
- Automatically send invoices to clients.

## 🧰 Technologies Used

- **Frontend**: Vue.js, Tailwind CSS, Schadcn-vue, Lucide
- **Backend**: Laravel 12, Sqlite

## ⚙️ Installation

### 🔧 Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and NPM

### 📦 Installation Steps

1. Clone this repository:

    ```bash
    git clone https://github.com/Hadrien-Janssens/sijetaisunefleur.git
    ```

2. Install PHP dependencies:

    ```bash
    cd sijetaisunefleur
    composer install
    ```

3. Install Node.js dependencies:

    ```bash
    npm install
    ```

4. Copy the .env.example file to create your .env file:

    ```bash
    cp .env.example .env
    ```

5. Configure your `.env` file with your **database**, **mailing** and **Admin Login** settings.

6. Generate the Laravel application key and the symbolic link:

    ```bash
    php artisan key:generate
    ```

7. Run the migrations to set up the database tables:

    ```bash
    php artisan migrate --seed
    ```

    > 💡 If you want fake data, go to `database/seeders/DatabaseSeeder.php` and uncomment all factories.

8. Start backend and frontend development server:

    ```bash
    npm run dev:all
    ```

9. Access the app with the provided URL.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) file for details.

## 👤 Author

- [Hadrien Janssens](https://github.com/Hadrien-Janssens)

## Demo

[![Regarder la démo](https://github.com/Hadrien-Janssens/sijetaisunefleur/raw/main/public/preview.png)](https://github.com/Hadrien-Janssens/sijetaisunefleur/blob/main/public/demo.mov?raw=true)
