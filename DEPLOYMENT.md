# Triển khai production

Ubuntu 22.04 hoặc 24.04, một domain đã trỏ A record về IP máy chủ. Thay `example.com` và mật khẩu bên dưới bằng giá trị thật.

## 1. Gói hệ thống

```bash
sudo apt update
sudo apt install -y nginx mysql-server git unzip curl
sudo ufw allow OpenSSH
sudo ufw allow "Nginx Full"
sudo ufw enable
```

Ubuntu 22.04 mặc định PHP 8.1, không đủ cho Laravel 12. Cả hai bản Ubuntu dùng kho PHP 8.3:

```bash
sudo add-apt-repository -y ppa:ondrej/php
sudo apt update
sudo apt install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring \
  php8.3-xml php8.3-curl php8.3-zip php8.3-bcmath php8.3-gd php8.3-intl
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
sudo apt install -y nodejs
```

Vite 7 cần Node 20.19+ hoặc 22.12+.

## 2. MySQL

```bash
sudo mysql
```

```sql
CREATE DATABASE tra_sua_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'trasua'@'localhost' IDENTIFIED BY 'mat-khau-manh';
GRANT ALL PRIVILEGES ON tra_sua_shop.* TO 'trasua'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## 3. Code và `.env`

```bash
sudo mkdir -p /var/www/quan-tra-sua
sudo chown "$USER":www-data /var/www/quan-tra-sua
git clone <url-repo> /var/www/quan-tra-sua
cd /var/www/quan-tra-sua
cp .env.example .env
php artisan key:generate
sudo usermod -aG www-data "$USER"
```

Đăng nhập lại để nhóm `www-data` có hiệu lực. Sửa `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tra_sua_shop
DB_USERNAME=trasua
DB_PASSWORD=mat-khau-manh

VIETQR_BANK_ID=
VIETQR_ACCOUNT_NO=
VIETQR_ACCOUNT_NAME=
```

`SESSION_DRIVER` và `CACHE_STORE` giữ `database`. Bảng được tạo lúc migrate.

## 4. Deploy

```bash
chmod +x deploy.sh
./deploy.sh
```

Script kéo code (`git pull --ff-only`), `composer install --no-dev --optimize-autoloader`, `npm ci` và `npm run build`, `php artisan migrate --force`, tạo `public/storage` nếu chưa có, xóa rồi nạp lại config/route/view cache, rồi `chown`/`chmod` `storage` và `bootstrap/cache` cho `www-data`.

Lần sau chỉ cần `./deploy.sh` trong thư mục dự án.

Không chạy `db:seed` trên production. Seeder tạo `admin@trasua.test`, `staff@trasua.test` và `khach@trasua.test` với mật khẩu `password`. Nếu đã seed lúc thử, đổi mật khẩu hoặc xóa các tài khoản đó trước khi mở bán.

## 5. Nginx

```bash
sudo cp nginx.conf.example /etc/nginx/sites-available/quan-tra-sua
sudo nano /etc/nginx/sites-available/quan-tra-sua
```

Sửa `server_name` và `root` cho đúng máy. `root` phải là thư mục `public`.

```bash
sudo ln -s /etc/nginx/sites-available/quan-tra-sua /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

## 6. SSL Let's Encrypt

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d example.com -d www.example.com
```

Certbot sửa Nginx để listen 443 và gia hạn chứng chỉ bằng timer sẵn của hệ thống. Kiểm tra:

```bash
sudo certbot renew --dry-run
```

Sau khi có HTTPS, xác nhận `APP_URL` trong `.env` là `https://example.com`, rồi chạy lại `./deploy.sh` để nạp `config:cache`.
