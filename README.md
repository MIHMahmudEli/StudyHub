# 🧠 StudyHub: Collaborative Student Notes & Slides Platform

**StudyHub** is a web-based platform that enables students to **share, browse, and manage academic notes and slides collaboratively**.  
With distinct roles for **Students**, **Moderators**, and **Admins**, the platform encourages **peer learning**, **content moderation**, and **gamified engagement** through points and badges.

---

## 🚀 Features

### 👩‍🎓 Student
- Upload and share notes (PDF, Word, Images, etc.)
- Search and download notes from others
- Rate, comment, and bookmark notes
- Earn points or badges for contributions

### 🧑‍💼 Moderator
- Approve or reject uploaded notes
- Flag low-quality or duplicate content
- Manage comments and user reports

### 🛠️ Admin
- Manage user accounts (block, delete, promote)
- View analytics (trending subjects, active users)
- Generate platform activity reports

---

## 🧩 Tech Stack
- **Frontend:** HTML, CSS, JavaScript, Bootstrap  
- **Backend:** PHP (XAMPP Environment)  
- **Database:** MySQL  
- **Authentication:** Role-based login system  

---

## 🗂️ Database Overview
| Table | Description |
|--------|-------------|
| `users` | Stores user data and roles |
| `notes` | Uploaded notes with metadata |
| `reviews` | Ratings and comments |
| `bookmarks` | User’s saved notes |
| `reports` | User-submitted content reports |
| `events` | Tracks uploads, downloads, and ratings |

> Full SQL setup available in [`studyhub_mysql_setup.sql`](./studyhub_mysql_setup.sql)

---

## ⚙️ Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/MIHMahmudEli/studyhub.git
   ```
2. Import the database file:
   - Open **phpMyAdmin**
   - Create a database named `studyhub`
   - Import `studyhub_mysql_setup.sql`
3. Configure your `config.php` file with local MySQL credentials:
   ```php
   $conn = new mysqli("localhost", "root", "", "studyhub");
   ```
4. Run the project using **XAMPP** or any PHP server:
   ```
   http://localhost/studyhub
   ```

---

## 📊 Future Enhancements
- Advanced search and filtering  
- AI-based note quality detection  
- Real-time chat/discussion threads  
- Dark/light theme toggle  

---

## 👥 Team
- **Yeahyea Jam** (ID: 23-50187-1)  
- **Mohsin Ibna Hossain** (ID: 23-50194-1)  

---

## 📜 License
This project is developed as part of the **Web Technologies (Summer 24–25)** course and is intended for academic purposes.

---

### 🌟 Contribute
Pull requests are welcome! For major changes, please open an issue first to discuss what you’d like to modify.
