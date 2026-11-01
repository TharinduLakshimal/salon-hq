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
| **Worker** | Dinesh Perera | `dinesh@salonhq.com` | `password` | ♂ Male Only (Senior Barber) |
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

## ✅ Current Project Progress & Architecture

### Completed Core Features

- [x] **Central Users & Multi-Role Authentication** (`AuthController.php`, `User.php`)
  - Universal login supporting `owner`, `worker`, and `client` roles with role-based routing.
  - Owners route to `/dashboard`, Workers route to `/appointments`, Clients route to `/my-dashboard`.
  - Secure phone number normalization (supports local `07X` and international `+94` formats).

- [x] **Dynamic Owner Dashboard (`/dashboard`)** (`DashboardController.php`, `resources/views/dashboard.blade.php`)
  - Real-time calculations: today's revenue, active appointments count, completed works, and pending requests.
  - Live daily appointment schedule feed ordered chronologically with client and stylist relations.
  - **Livewire Stylist Status Widget**: Instant staff status toggling (`Available` ↔ `In Session` ↔ `On Break` ↔ `Off Duty`).

- [x] **Unisex Staff & Stylists Management (`/staff`, `/staff/{id}`)** (`StaffController.php`, `StaffDirectory.php`)
  - Department categorization (`Bridal & Makeup`, `Hair Stylists`, `Barbers`, `Estheticians & Nails`).
  - Unisex client gender filtering (`Both`, `Female Only`, `Male Only`).
  - Modal-based staff creation, editing, and profile pages with performance metrics and portfolio works.

- [x] **Worker Portal & Profile Management (`/my-profile`, `/appointments`)** (`WorkerProfileController.php`)
  - Securely scopes workers strictly to their assigned appointments.
  - Allows stylists to update personal info, phone, bio, specialties, password, and instant availability status.
  - Protects sensitive financial configurations (commission rates, category, job title) from unauthorized changes.

- [x] **Services & Pricing Catalog (`/services`)** (`ServiceController.php`)
  - Department-scoped service catalog with pricing, duration in minutes, and package designations.
  - Target gender tagging (`ladies`, `gents`, `both`).
  - Full CRUD operations with modal interfaces and validation.

- [x] **Appointments & Scheduling Management (`/appointments`)** (`AppointmentController.php`)
  - Unified booking lifecycle (`pending`, `confirmed`, `in_progress`, `completed`, `cancelled`).
  - Unique sequential booking codes (`BK-YYYY-XXXX`).
  - Real-time status update workflow with POS integration.
  - Client phone number auto-lookup (`/clients/search-phone`) for rapid walk-in and phone desk bookings.

- [x] **Point of Sale (POS) & Automated Staff Metrics** (`AppointmentObserver.php`, `AppServiceProvider.php`)
  - Eloquent model observer listening to appointment lifecycle changes.
  - Automatically increments assigned stylist's `completed_works` and adds appointment price to `monthly_revenue` upon status completion.

- [x] **Client CRM & Visit History (`/clients`, `/clients/{id}`)** (`ClientController.php`)
  - Real-time aggregation of `total_visits` and `total_spend` on completed appointments using Eloquent `withCount` and `withSum`.
  - Detailed client dossier displaying full appointment history, past treatments, assigned stylists, and spend receipts.

- [x] **Dedicated Client Portal (`/my-dashboard`)** (`ClientAreaController.php`)
  - Customer-facing dashboard for registered clients to track active reservations, review booking codes, and inspect service history.

- [x] **Public Online Booking Portal (`/book`)** (`PublicBookingController.php`, `resources/views/public/booking.blade.php`)
  - Responsive, multi-step customer booking flow powered by Alpine.js and Livewire assets.
  - **Gender-Tailored Service Filtering:** Choose `Ladies`, `Gents`, or `All` at Step 1 to instantly filter haircuts and treatments with live category count badges.
  - **Dynamic Barber Time Slot Availability:** Checks database appointments specifically for the selected stylist and date; automatically strikes through booked slots and auto-selects available slots.
  - **Real-Time Availability API (`GET /book/availability`):** Asynchronously synchronizes slot states when switching dates or barbers.
  - **Anti-Double-Booking Protection:** Server-side interval collision checks prevent race conditions and overlapping bookings.
  - **Instant Guest Onboarding:** Automatically generates or links client user profiles on checkout without requiring pre-registration.

---

## 🧪 Testing Suite

This project includes feature and unit test coverage across all domain areas using PHPUnit:

```bash
# Run all tests
php artisan test

# Run tests with compact summary
php artisan test --compact

# Run specific domain test suites
php artisan test --filter=PublicBookingTest
php artisan test --filter=AppointmentCheckoutTest
php artisan test --filter=ClientManagementTest
php artisan test --filter=DashboardTest
php artisan test --filter=WorkerPortalTest
```

**Test Status:** 70/70 passing tests (358 assertions).

---

## 🗺️ Upcoming Modules

- [ ] **Inventory & Salon Products Management** (Product stock, supplier tracking, and retail checkout)
- [ ] **SMS & WhatsApp Booking Confirmations** (Automated appointment reminders and status alerts)
- [ ] **Advanced Financial Reports & Commission Payouts** (Daily cash reconciliation, staff commission settlements, and tax exports)
