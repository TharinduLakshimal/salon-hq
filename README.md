# ✂️ Salon HQ — Unisex Salon Management System

**Salon HQ** is a full-featured **Unisex Salon Management System** designed to manage salon owners, stylists/barbers, clients, appointments, services, inventory, and POS billing.

---

## 🛠️ Tech Stack (TALL Stack)

- **Backend:** Laravel 13 (**PHP 8.5+**)
- **Reactive UI:** Livewire 4 & Alpine.js (bundled with Livewire)
- **Styling & Assets:** Tailwind CSS v4 & Vite
- **Database:** MySQL (`salon_hq`)

---

## 🚀 How to Set Up After Cloning from GitHub / GitLab

### 1. Prerequisites
Make sure the following are installed on your computer:
- **PHP 8.5 or higher (Required)**
- **Composer**
- **Node.js & NPM**
- **MySQL Server** (e.g., Laragon, XAMPP, or Laravel Herd)

#### 💡 How to Download & Add PHP 8.5 (For Laragon / Windows Users)
If your `php -v` shows an older PHP version, follow these steps to install **PHP 8.5**:

1. **Download PHP 8.5 for Windows:**
   - Go to **[https://www.php.net/downloads.php?os=windows](https://www.php.net/downloads.php?os=windows)**
   - Download the **PHP 8.5 Thread Safe (`Win32-vs17-x64.zip`)** ZIP package.
2. **If You Use Laragon:**
   - Extract the downloaded ZIP folder into Laragon's PHP directory:
     ```text
     C:\laragon\bin\php\php-8.5.x-Win32-vs17-x64
     ```
   - Open **Laragon** → **Right-click** inside the window → **PHP** → **Version** → select **`php-8.5.x-Win32-vs17-x64`**.
   - Click **Menu → Tools → Path → Add Laragon to Path**, then restart your terminal and verify with `php -v`.
3. **Alternative (Quick PowerShell Install without Laragon):**
   ```powershell
   Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.5'))
   ```

---

### 2. Step-by-Step Installation

Open your terminal (PowerShell, Command Prompt, or Git Bash) and run:

```bash
# 1. Clone the repository
git clone https://github.com/TharinduLakshimal/salon-hq.git
cd salon-hq

# 2. Install PHP & Frontend dependencies
composer install
npm install

# 3. Create the .env file and generate the application key
cp .env.example .env
php artisan key:generate
```

---

### 3. Configure MySQL Database

1. Start **MySQL** in your **XAMPP Control Panel** (or local MySQL server).
2. Check your `.env` file to make sure the database connection is set to `mysql`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=salon_hq
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Run the database migrations and seed the default users and salon staff:
   ```bash
   php artisan migrate --seed
   ```
   *(If prompted `The database 'salon_hq' does not exist... Would you like to create it?`, type **`yes`**).*

---

### 4. Build Frontend Assets & Start the Server

```bash
# Build Tailwind CSS v4 assets
npm run build

# Start the Laravel development server
php artisan serve
```

Now open **[http://localhost:8000/login](http://localhost:8000/login)** in your browser!

*(During active frontend development, you can also run `composer run dev` or `npm run dev` in a second terminal).*

---

## 🔑 Default Test Accounts (Seeded Automatically)

All seeded accounts use the password: **`password`**

| Role | Name | Email | Password | Serves / Type |
| :--- | :--- | :--- | :--- | :--- |
| **Owner (Admin)** | Salon Owner | `owner@salonhq.com` | `password` | Full Admin Access |
| **Owner (Admin)** | Test Admin | `test@example.com` | `password` | Full Admin Access |
| **Worker** | Kavindi Senanayake | `kavindi@salonhq.com` | `password` | ♀ Female Only (Bridal & Makeup) |
| **Worker** | Roshan Mendis | `roshan@salonhq.com` | `password` | ♂ Male Only (Master Barber) |
| **Worker** | Amaya Jayawardena | `amaya@salonhq.com` | `password` | ⚥ Both Unisex (Hair Color & Keratin) |
| **Worker** | Nadeesha Karunaratne | `nadeesha@salonhq.com` | `password` | ⚥ Both Unisex (Esthetician) |
| **Worker** | Shenal Wickramasinghe | `shenal@salonhq.com` | `password` | ⚥ Both Unisex (Hair Stylist) |
| **Worker** | Tashya Rodrigo | `tashya@salonhq.com` | `password` | ♀ Female Only (Nails & Lashes) |
| **Client** | Nethmi Perera | `client@salonhq.com` | `password` | Customer Account |
| **Client** | Dilshan Silva | `dilshan@example.com` | `password` | Customer Account |

To reset the database and re-seed fresh test data at any time:
```bash
php artisan migrate:fresh --seed
```

---

## ✅ Current Project Progress

### Completed Features
- [x] **Central Users Table & Seeder** (`database/migrations/0001_01_01_000000_create_users_table.php`, `UserSeeder.php`)
  - Supports `owner`, `worker`, and `client` roles, `gender`, `phone_number`, and email verification timestamp.
- [x] **Authentication System** (`AuthController.php`, `resources/views/auth/login.blade.php`)
  - Clean Tailwind CSS v4 login page, session regeneration, and logout protection.
- [x] **Master Admin Layout & Reusable Sidebar** (`resources/views/layouts/app.blade.php`, `resources/views/components/sidebar.blade.php`)
  - Responsive sidebar with Alpine.js mobile drawer toggle and instant `wire:navigate` page transitions.
- [x] **Admin Overview Dashboard (`/dashboard`)**
  - KPI stat cards, Today's Appointments table, Quick Actions grid, and a **Livewire Stylist Status Widget** (`StylistStatusWidget.php`) that lets admins toggle staff availability (`Available` → `In Session` → `On Break`) in MySQL with 1 click.
- [x] **Unisex Staff & Stylists Management (`/staff`)**
  - Database migration (`staff` and `staff_works` tables), Eloquent models (`Staff`, `StaffWork`), and `StaffSeeder`.
  - **Livewire Staff Directory** (`StaffDirectory.php`): Real-time search and filtering by Department (`Bridal & Makeup`, `Hair Stylists`, `Barbers`, `Estheticians & Nails`) and Unisex Client Gender (`⚥ Both`, `♀ Female Only`, `♂ Male Only`).
  - **Livewire + Alpine.js Add Staff Modal**: Create new staff members and save directly to MySQL without page reloads.
- [x] **Individual Staff Profile & Portfolio Works Page (`/staff/{id}`)**
  - Displays stylist bio, contact info, working hours, performance KPIs, and completed portfolio works with Alpine.js tabs.

### Upcoming Modules (To Be Built Next)
- [ ] **Services & Pricing Management** (Male, Female & Unisex service catalog)
- [ ] **Appointments & Booking Calendar**
- [ ] **Clients / Customer Management**
- [ ] **Inventory & Salon Products**
- [ ] **POS & Billing / Invoicing**
- [ ] **Reports & Analytics**
