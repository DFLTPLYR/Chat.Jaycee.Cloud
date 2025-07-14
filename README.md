# Chat.Jaycee.Cloud

A real-time chat platform built with **Laravel**, **Inertia.js**, and **Vue 3**, using **Pusher** for WebSocket communication. Powered by modern tooling like **Vite**, **TypeScript**, **TailwindCSS**, and Prettier for clean, maintainable code.

---

## 🚀 Features

- 🔄 Real-time messaging with Pusher
- 🟢 Presence channels for online users
- ✍️ Typing indicators via `whisper` events
- ⚡ Fast SPA-like experience with Inertia.js
- 🎨 Styled with TailwindCSS (with animation support)
- 🔧 Dev-friendly tools: Prettier, ESLint, TypeScript
- 💡 Component-based UI using `reka-ui` and `lucide-vue-next`

---

## 🛠️ Tech Stack

| Layer         | Stack / Tool                   |
|---------------|---------------------------------|
| Backend       | Laravel 11                     |
| Frontend      | Vue 3 + TypeScript             |
| Bridge        | Inertia.js                     |
| WebSockets    | Pusher Channels                |
| Styling       | TailwindCSS 4 + plugins        |
| Build Tool    | Vite 6                         |
| Linting       | ESLint + Prettier              |
| Icons         | lucide-vue-next                |
| UI Components | reka-ui                        |

---

## ⚙️ Getting Started

### 1. Clone the project

```bash
git clone git@github.com:DFLTPLYR/Chat.Jaycee.Cloud.git
cd Chat.Jaycee.Cloud
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate

Update .env with your Pusher credentials:

BROADCAST_DRIVER=pusher

PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1

QUEUE_CONNECTION=sync # or 'redis' if you're using queues
```
