# WordPress Sites

这个仓库统一管理同一台服务器上的多个独立 WordPress 站点。

## 站点

```text
wordpress-sites/
└── aromamatrix-shop/
    ├── compose.yaml
    ├── theme/
    ├── plugin/
    ├── nginx/
    ├── php/
    └── scripts/
```

`aromamatrix-shop` 是 `shop.aromamatrix.com` 的独立 WordPress、MariaDB 与
Redis 部署。新增站点时应使用新的子目录、Compose 项目名、宿主机端口、
数据库凭据、数据卷与备份目录。

## AROMAMATRIX Shop

```bash
cd aromamatrix-shop
docker compose config --quiet
docker compose ps
```

主题和插件部署：

```bash
cd aromamatrix-shop
./scripts/deploy-all.sh
```

详细说明见 [aromamatrix-shop/README.md](aromamatrix-shop/README.md)、
[aromamatrix-shop/DEVELOPMENT.md](aromamatrix-shop/DEVELOPMENT.md) 和
[aromamatrix-shop/DEPLOYMENT.md](aromamatrix-shop/DEPLOYMENT.md)。
