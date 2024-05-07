# การติดตั้งโครงงาน

ผู้ใช้สามารถดำเนินการติดตั้งระบบได้ตามขั้นตอนต่อไปนี้

1. ดาวน์โหลดและติดตั้งโปรแกรม Docker Desktop จาก [https://www.docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop)
2. ทำการ Clone โครงงานจาก [https://github.com/Akanitt/disboard](https://github.com/Akanitt/disboard).
3. แก้ไขชื่อไฟล์ `.env.example` สู่ `.env` และแก้ไขข้อมูลตัวแปรในไฟล์ `.env`. สำหรับเครื่องคอมพิวเตอร์เครื่องผู้ใช้
4. แก้ไขข้อมูลในไฟล์ `compose.ymal` ดังต่อไปนี้
    4.1	พารามิเตอร์ `environment` ของ service `server`  โดยมีค่าเดียวกับข้อมูลในไฟล์ `.env`
    4.2	พารามิเตอร์ `environment` ของ service `db` โดยมีค่าดังนี้
        - `MARIADB_DATABASE`: ชื่อของฐานข้อมูล
        - `MARIADB_ROOT_PASSWORD`: รหัสผ่านของผู้ใช้ในการเข้าถึงฐานข้อมูล
5. เปิดโปรแกรม terminal ในไดเรกทอรีโครงงาน และรันคำสั่ง `docker compose up --build`

# Project Setup Instructions

Follow the steps below to set up the system:

1. Download and install Docker Desktop from [https://www.docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop).
2. Clone the project from [https://github.com/Akanitt/disboard](https://github.com/Akanitt/disboard).
3. Edit the variables in the `.env` file according to your machine's configuration.
4. Modify the `compose.yaml` file as follows:
   4.1. Update the `environment` parameters of the `server` service to match the data in the `.env` file.
   4.2. Update the `environment` parameters of the `db` service with the following values:
       - `MARIADB_DATABASE`: The name of the database.
       - `MARIADB_ROOT_PASSWORD`: The password for accessing the database.
5. Open a terminal in the project directory and run the command `docker-compose up --build`.