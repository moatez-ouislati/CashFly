# CashFly

CashFly is a comprehensive web platform designed to connect business owners (Propriétaires) with investors (Investisseurs). The application provides robust tools for managing companies, tracking financial operations, facilitating investments, and leveraging modern AI features to streamline workflows.

---

## 🌟 Roles & Permissions

- **Administrateur (Admin)**: Full oversight over the platform. Can manage users (CRUD), companies, operations, documents, and view platform-wide statistics. Has access to the Admin Dashboard and uses mandatory Face Verification on login.
- **Propriétaire (Business Owner)**: Can create and manage their companies (PME), view financial operations, manage treasuries (Trésorerie), interact with investors, and upload related documents.
- **Investisseur (Investor)**: Can explore companies and business owners, track their investments, monitor yield (Rendement), and view company locations on the interactive map. Must provide their investment budget and experience during profile setup.

---

## 🚀 Full Feature List Per Module

### 1. 🏢 Gestion des Entreprises (Company Management)
- **Profile Creation & Editing**: Business owners can manage their company details.
- **Automatic Owner Assignment**: The logged-in Propriétaire is securely linked to the newly created company.
- **Auto-Tresorerie Creation**: Whenever a new company is created, its initial capital is automatically deposited into a newly created Treasury account with an initial revenue operation.
- **Geocoding & Maps**: Set company coordinates via an integrated map (Leaflet & OpenStreetMap API) to automatically pull latitude and longitude.
- **Interactive Global Map**: 
  - Owners see a map of only their own companies.
  - Admins and Investors can view a global map of all companies registered on the platform.

### 2. 💰 Trésorerie & Opérations (Treasury & Financial Operations)
- **Treasury Accounts**: Manage financial accounts (Bank, Cash, Wallet, etc.).
- **Multi-Currency Support**: Add operations in different currencies with automatic real-time conversion to TND using external exchange rate APIs.
- **Internal Transfers (Virements)**: Seamlessly transfer funds between different treasury accounts belonging to the same company.
- **Operations Tracking**: Track revenues and expenses linked to specific treasuries.
- **Cashflow Prediction & Forecasting**: Automatically analyzes 90 days of historical data to project 30 days into the future. Highlights potential deficits and alerts owners. Accounts for multiple treasuries dynamically with ApexCharts.
- **Liquidity Ratio Calculation**: Automatically assesses the health of a company using assets/liabilities to provide a clear indicator (Excellent, Acceptable, Critical).
- **Anomaly Detection**: Warns the owner if an operation's amount significantly deviates (e.g. 200%) from the historical average of its category.
- **PDF Reports**: Automatically generate beautifully formatted PDF receipts for specific financial operations (via Dompdf).

### 3. 📄 Documents & OCR
- **Document Vault**: Upload and manage company-related documents (Contracts, Invoices, Reports).
- **Secure File Storage**: Validation for various file formats (PDF, DOCX, PNG, JPG) and size limits.
- **OCR Integration**: Optical Character Recognition to automatically extract text and data from uploaded documents and images.
- **Document Workflow**: Automated workflows triggered by document statuses.

### 4. 🤝 Investissements & Rendements (Investments & Yields)
- **Investor Exploration**: Business owners can browse through verified investors.
- **Obligatory Investor Profiling**: Investors are strictly required to fill out their budget and years of experience to access investment opportunities.
- **Investment Tracking & Linking**: Investors can link their investments directly to specific enterprises and track their status (EN ATTENTE, ACTIF).
- **Advanced Yield Statistics**:
  - **Portfolio Evolution**: Interactive ApexCharts area charts showing monthly capital growth over 12 months.
  - **Sector Distribution**: Real-time donut charts illustrating capital allocation across business sectors.
  - **Performance Metrics**: Automated calculation of Average Yield, Expected Total Gain, and Actual ROI (Return on Investment).
- **Admin Oversight**: Dedicated administration panel to monitor all platform-wide investments, ensuring transparency and security.

### 5. 🤖 Intelligence Artificielle (AI Suite)
- **Smart Advisor (Mistral/Llama)**: An integrated AI consultant based on Mistral-7B or Llama-3.2 (via NVIDIA NIM API) providing automated, personalized investment suggestions based on user risk profiles.
- **Chatbot Expert**: A conversational expert that understands natural language to retrieve treasury data or answer complex financial questions.
- **Automated Actions**: Can execute specific commands automatically based on user prompts (like generating a new expense or revenue).
- **Risk Scoring & Analysis**: Advanced risk assessment logic classifying profiles into risk levels (Low, Moderate, High, Critical) based on financial health and investment volume.
- **Market Intelligence**: Integrated financial hub providing real-time global news, currency exchange rates (EUR/USD/GBP to TND), and World Bank economic indicators.

### 6. 🔐 Sécurité & Authentification
- **Google OAuth Integration**: Fast and secure login/registration using Google accounts.
- **Admin Face Verification**: Strict AI-powered Face ID verification required for Administrators before accessing the backend dashboard.
- **Face ID Profile Uploads**: Advanced KYC (Know Your Customer) module capturing user face images during profile updates.
- **Role-Based Access Control (RBAC)**: Deep security layer ensuring users only access their own data.
- **Admin Email Notifications**: Automatic email alerts sent to administrators when a user updates sensitive profile data (e.g., CIN, Face Image).

---

## 🛠️ Technologies Used
- **Backend**: Symfony 7.x, PHP 8.2+, Doctrine ORM
- **Frontend**: Twig, Tailwind CSS (via CDN), AssetMapper, Vanilla JS, Leaflet (Maps), ApexCharts, Chart.js
- **Security**: Symfony Security Component, KnpU OAuth2 Client, face-api.js
- **PDF Generation**: Nucleos Dompdf Bundle
- **Database**: MySQL / MariaDB

---

## ⚙️ Installation & Setup

1. **Clone the repository**
   ```bash
   git clone <repository_url>
   cd cashfly
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Configure Environment Variables**
   Copy `.env` to `.env.local` and configure your database connection and API keys:
   ```env
   DATABASE_URL="mysql://root:@127.0.0.1:3306/cashfly?serverVersion=8.0.32&charset=utf8mb4"
   ```

4. **Database Setup**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Start the local web server**
   ```bash
   symfony server:start
   ```

---

## 🏗️ Architecture & Structure
CashFly is built following the traditional **MVC (Model-View-Controller)** pattern heavily utilized by Symfony.

- **Model (Entities & Repositories)**: Found in `src/Entity` and `src/Repository`. Doctrine ORM maps these classes directly to database tables. Business logic related to data retrieval is kept in the repositories.
- **View (Twig Templates)**: Found in the `templates/` directory. We use a modular approach with a common layout (`base.html.twig`) extended by module-specific pages (e.g., `templates/entreprise/index.html.twig`). Tailwind CSS is utilized for styling.
- **Controller**: Found in `src/Controller`. Handles routing (`#[Route]`), HTTP requests, permissions (`#[IsGranted]`), and passes data to Twig.
- **Services**: Complex business logic, API integrations, and AI tasks are abstracted into reusable services inside `src/Service` (e.g., `ChatbotService`, `TresorerieAutomationService`).

---

## 🛠️ How to Add a New Page

To add a new page or feature to the project, follow this best-practice workflow:

1. **Create the Route & Controller**
   Run the maker bundle or manually create a file in `src/Controller`:
   ```bash
   php bin/console make:controller MyNewController
   ```
   Define your route and enforce permissions:
   ```php
   #[Route('/my-new-page', name: 'app_my_new_page')]
   #[IsGranted('ROLE_PROPRIETAIRE')]
   public function index(): Response
   {
       return $this->render('my_new_page/index.html.twig', [
           'variable' => 'Hello World',
       ]);
   }
   ```

2. **Create the Template**
   Create `templates/my_new_page/index.html.twig` and extend the base layout to inherit the sidebar and styling:
   ```twig
   {% extends 'base.html.twig' %}

   {% block title %}My New Page{% endblock %}

   {% block body %}
       <div class="bg-white p-6 rounded-2xl shadow-sm">
           <h1 class="text-2xl font-bold">Welcome to my new page!</h1>
           <p>{{ variable }}</p>
       </div>
   {% endblock %}
   ```

3. **Link it in the Sidebar**
   Open `templates/base.html.twig` and locate the navigation links for your specific role. Add a new menu item pointing to your route:
   ```twig
   <a href="{{ path('app_my_new_page') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300">
       <span class="material-symbols-outlined">star</span>
       <span class="text-sm font-medium">My New Page</span>
   </a>
   ```

---

## 🧪 Testing & Quality Assurance

To ensure the platform's reliability and code quality, several testing and analysis tools are integrated:

### 1. Unit Testing (PHPUnit)
We use PHPUnit to validate business logic and entity integrity.
- **Run all tests**:
  ```bash
  vendor/bin/phpunit
  ```
- **Coverage**: Includes `User`, `Entreprise`, `Operation`, and `Tresorerie` entities.

### 2. Static Analysis (PHPStan)
PHPStan is used to find bugs in the code without actually running it.
- **Run analysis**:
  ```bash
  vendor/bin/phpstan analyze src --level 5
  ```

### 3. Database Schema Validation
To ensure the database remains in sync with the Doctrine mapping:
- **Validate schema**:
  ```bash
  php bin/console doctrine:schema:validate
  ```

---

## 👥 Authors
Built by the **CashFly Team** as part of the PI Dev Project.