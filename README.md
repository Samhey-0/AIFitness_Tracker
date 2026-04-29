# AIFitness_Tracker - AI-Powered Fitness & Nutrition

AIFitness_Tracker is a professional-grade web application designed to bridge the gap between data tracking and intelligent coaching. Built using **Laravel 11**, it leverages the **Gemini 1.5 Flash API** to provide users with instant, customized workout and nutrition plans based on their specific goals and available equipment.

📸 Screenshots

## 🚀 Key Features

-   **AI Coach & Nutritionist:** Instant generation of dual-track plans (Workout + Diet) using Google's Gemini AI.
-   **Dynamic Activity Tracking:** Professional workout timer and live step counter powered by **Alpine.js**.
-   **No-NPM Architecture:** High-end performance using Tailwind CSS and Alpine.js via CDN for a zero-build-step deployment.
-   **Glassmorphic UI:** A clean, modern interface focused on premium UX and mobile responsiveness.
-   **Progress Analytics:** Visualized data tracking using **Chart.js**.

## 🛠️ Tech Stack

-   **Backend:** PHP 8.2+ | Laravel 11
-   **Frontend:** Tailwind CSS | Alpine.js
-   **AI Engine:** Gemini 1.5 Flash API
-   **Charts:** Chart.js
-   **Icons:** Lucide Icons

## ⚙️ Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/yourusername/FitTrack-Pro-AI.git](https://github.com/yourusername/FitTrack-Pro-AI.git)
   cd FitTrack-Pro-AI
Install PHP Dependencies:

Bash
composer install
Environment Setup:

Bash
cp .env.example .env
php artisan key:generate
Configure your AI Keys:
Add your Gemini credentials to the .env file:

Code snippet
GEMINI_API_KEY=your_api_key_here
GEMINI_API_URL=[https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent](https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent)
Run Migrations:

Bash
php artisan migrate
Serve the App:

Bash
php artisan serve

Developed by Saim Bakhtiar

