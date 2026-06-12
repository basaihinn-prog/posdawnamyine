# Production Setup Scripts

This folder contains helper scripts for preparing the PosDreamPool app for a local production-style deployment.

## `setup_production.sh`

Use this script to configure the Laravel app in production mode, install dependencies, run migrations, build front-end assets, and optionally configure Nginx or systemd.

Example:

```bash
cd /mnt/B012BC6C12BC3964/ProductionPROjeects/pos/PosDreamPool
chmod +x scripts/setup_production.sh
./scripts/setup_production.sh --host 0.0.0.0 --port 8000 --app-url http://192.168.1.100:8000
```

### Optional flags

- `--no-install`: skip Composer and node dependency installation
- `--no-build`: skip front-end build
- `--no-migrate`: skip database migrations
- `--no-cache`: skip Laravel cache generation
- `--enable-nginx`: generate and enable an Nginx site config
- `--enable-systemd`: generate and enable a systemd Laravel queue worker
- `--nginx-server-name NAME`: set the Nginx `server_name`
- `--service-user USER`: user for the systemd service
- `--service-name NAME`: systemd service name

## `nginx_pos_site.conf.tpl`

A template example for the Nginx site configuration. It is not required by the script, but can be used as a reference when installing Nginx manually.

## `pos.service.tpl`

A template example for a systemd service. The script generates a live service file directly.
