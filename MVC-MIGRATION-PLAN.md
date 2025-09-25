# MVC Yapısına Geçiş Planı

## Mevcut Durum
- Tek dosya yapısı (index.php, admin-panel.php)
- HTML ve PHP kod karışık
- Tekrar eden kodlar
- Zor bakım

## Hedef MVC Yapısı

```
ders_programi/
├── app/
│   ├── Controllers/
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   ├── ProgramController.php
│   │   ├── TeacherController.php
│   │   └── ScheduleController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Program.php
│   │   ├── Teacher.php
│   │   ├── Course.php
│   │   └── Schedule.php
│   ├── Views/
│   │   ├── layouts/
│   │   │   ├── admin.php
│   │   │   └── guest.php
│   │   ├── admin/
│   │   │   ├── dashboard.php
│   │   │   ├── programs.php
│   │   │   └── teachers.php
│   │   └── auth/
│   │       └── login.php
│   └── Middleware/
│       ├── AuthMiddleware.php
│       └── PermissionMiddleware.php
├── config/
│   ├── database.php
│   ├── auth.php
│   └── routes.php
├── public/
│   ├── index.php (entry point)
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
├── core/
│   ├── Router.php
│   ├── Controller.php
│   ├── Model.php
│   └── View.php
└── vendor/ (composer)
```

## Geçiş Adımları

### 1. Core System
- Router sınıfı
- Base Controller sınıfı
- Base Model sınıfı
- View engine

### 2. Models
- User model (admin_users tablosu)
- Program model
- Teacher model
- Course model
- Schedule model

### 3. Controllers
- AdminController (dashboard)
- AuthController (login/logout)
- ProgramController (CRUD)
- TeacherController (CRUD)
- ScheduleController (program yönetimi)

### 4. Views
- Layout sistemı
- Component yapısı
- Template inheritance

### 5. Middleware
- Authentication check
- Permission control
- CSRF protection

## Avantajları

✅ **Organized Code**: Her şey yerinde
✅ **Reusable Components**: Tekrar eden kodları azaltır
✅ **Easy Maintenance**: Kolay bakım
✅ **Security**: Daha güvenli
✅ **Testing**: Test edilebilir kod
✅ **Scalability**: Büyümeye hazır