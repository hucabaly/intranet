RikkeiSoft's Intranet System
===

This is Intranet system of RikkeiSoft Co. Ltd.,   

Development
---

1. Clone source code

   ```
   $ git clone git@gitlab.rikkei.org:production/intranet.git /path/to/project/source
   ```

2. Mount source code to VM

   ```
   $ sed -i '/^my_app_source_path/cmy_app_source_path = "/path/to/project/source"' /path/to/project/vagrant/Vagrantfile
   ```

3. Edit host file

   ```
   $ sudo echo "10.10.10.10 rikkei.dev >> /etc/hosts"
   ```

4. Edit source code for fixed bugs or added features

5. Open http://rikkei.dev

Modules
---
Có 12 modules được chia theo nhóm các chức năng. 

STT | Module | Chức năng
--- | ------ | ---------
01 | [Core][md-core] | Cơ bản: authenticate, access control, enable/disable maintenance mode
02 | [Accounting][md-accounting] | nghiệp vự kế toán, xuất bảng lương
03 | [Assets][md-assets] | quản lý tài sản, order mượn thiết bị, booking phòng họp
04 | [Customer][md-customer] | quản lý thông tin khách hàng
05 | [Employee][md-employee] | quản lý nhân viên, các chức năng của mỗi nhân viên
06 | [Music][md-music] | Order phát nhạc và quản lý phát nhac theo yêu cầu
07 | [NEWS][md-news] | Quản lý bảng tin, các quy định, tin tức nội bộ
08 | [Project][md-project] | Quản lý thông tin dự án, CSS của dự án
09 | [Team][md-team] | Quản lý team
10 | [Training][md-training] | Quản lý các hoạt động đào tạo, kiểm tra nhân viên định kỳ về ISMS, ...
11 | [Working][md-working] | Quản lý việc chấm công, là thêm giờ
12 | [Recruitment][md-recruitment] | Phần tuyển dụng và kiểm tra ứng viên

Mỗi module được viết như một package và có cấu trúc như sau:

```
module_name
|-- composer.json
|-- config
|   |-- other_config.php
|   `-- routes.php
|-- README.md
|-- resources
|   |-- lang
|   `-- views
|-- src
|   |-- Console
|   |-- Events
|   |-- Exceptions
|   |-- Http
|   |   |-- Controllers
|   |   |-- Middleware
|   |   `-- Requests
|   |-- Jobs
|   |-- Listeners
|   |-- Model
|   |-- Policies
|   |-- Providers
|   `-- ServiceProvider.php
`-- tests
```

Trong đó:

- File **composer.json** chứa thông tin định nghĩa về package theo định dạng của **Composer**.
  Khi một module yêu cầu 1 package nào đấy thì mọi người nên cập nhật vào file này và file `composer.json` 
  ở thư mục gốc của project

- Thư mục **config** chứa các file cấu hình cho module như `routes.php`, ... 
  Chúng sẽ được chỉ định load bởi các class Provider của bạn. Xem class `Rikkei\Core\Providers\RouteServiceProvider`
  để thấy cách load file `config/routes.php`

- File **README.md** mô tả về module: chức năng, ...
- Thư mục **resource** chứa các resource của module như view scripts, language files, sass, ...
  Xem class `Rikkei\Core\ServiceProvider` để thấy cách load view scripts

- Thư mục **src** chứa các class của module: controller, model, policie, provider, ... 
  Mỗi module luôn có 1 class `ServiceProvider` để load module đó

- Thư mục **tests** chứa nội dung unit tests của module


[md-core]:         ./modules/core/README.md
[md-accounting]:   ./modules/accounting/README.md
[md-assets]:       ./modules/assets/README.md
[md-customer]:     ./modules/customer/README.md
[md-employee]:     ./modules/employee/README.md
[md-music]:        ./modules/music/README.md
[md-news]:         ./modules/news/README.md
[md-project]:      ./modules/project/README.md
[md-recruitment]:  ./modules/recruitment/README.md
[md-team]:         ./modules/team/README.md
[md-training]:     ./modules/training/README.md
[md-working]:      ./modules/working/README.md