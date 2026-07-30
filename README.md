# WordPress + Nginx + MariaDB

這是一套適合部署在單台 VPS 的 Docker Compose 設定：

- Nginx 對外提供 HTTP
- WordPress 使用 PHP-FPM
- MariaDB 只存在於 Docker 內部網路，不公開資料庫連接埠
- WordPress 檔案與資料庫分別保存在 Docker named volumes

## 啟動

需求：Docker Engine 與 Docker Compose v2。

```bash
cp .env.example .env
```

編輯 `.env`，至少替換以下兩個密碼：

```dotenv
MARIADB_PASSWORD=請換成高強度隨機密碼
MARIADB_ROOT_PASSWORD=請換成另一組高強度隨機密碼
```

檢查設定並啟動：

```bash
docker compose config
docker compose pull
docker compose up -d
docker compose ps
```

預設開啟：

```text
http://伺服器IP:8080
```

第一次進入時，依 WordPress 安裝畫面建立網站管理員。請不要使用 `admin` 作為管理員名稱。

## 常用操作

查看日誌：

```bash
docker compose logs -f --tail=100
```

停止服務但保留資料：

```bash
docker compose down
```

重新啟動：

```bash
docker compose up -d
```

> 請勿執行 `docker compose down -v`，這會刪除 WordPress 與資料庫 volumes。

## 備份

建立資料庫備份：

```bash
mkdir -p backups
docker compose exec -T db mariadb-dump \
  -u root \
  -p"$MARIADB_ROOT_PASSWORD" \
  --single-transaction \
  --routines \
  --triggers \
  "$MARIADB_DATABASE" | gzip > "backups/wordpress-$(date +%F-%H%M%S).sql.gz"
```

上面的命令會從目前 shell 讀取環境變數。可先執行：

```bash
set -a
source .env
set +a
```

WordPress 檔案也必須另外備份，尤其是 `/var/www/html/wp-content`。可用以下命令輸出 named volume：

```bash
docker compose exec -T wordpress tar -C /var/www/html -czf - wp-content \
  > "backups/wp-content-$(date +%F-%H%M%S).tar.gz"
```

## HTTPS

目前 Nginx 只監聽 HTTP。正式上線時，請在它前方配置負責 TLS 的反向代理或負載平衡器，並把：

```dotenv
HTTP_BIND_IP=127.0.0.1
```

反向代理需要傳遞至少以下標頭：

```nginx
proxy_set_header Host $host;
proxy_set_header X-Real-IP $remote_addr;
proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
proxy_set_header X-Forwarded-Proto $scheme;
```

不要在尚未設定 HTTPS、防火牆及備份以前直接對外正式營運。
