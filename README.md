# Building app
In order to build app, please execute following commands in shell of your choice
```bash
docker compose build
```
```bash
docker compose up
```
# Database Operations

## Database Container Access

```bash
docker exec -it postgres psql -U appuser -d appdb
```

## Creating Admin user

```bash
INSERT INTO users (first_name, last_name, address, phone, email, password, is_admin)
VALUES ('FIRST_NAME', 'LAST_NAME', 'ADDRESS', 'PHONE_NUMBER', 'EMAIL@BOCHNIA.CITY', crypt('PASSWORD', gen_salt('bf')), TRUE);
```
## Following extension must be present in order to add Admin to database
If it's not included simply execute following query before inserting Admin account to database:  

```bash
CREATE EXTENSION IF NOT EXISTS pgcrypto;
```
