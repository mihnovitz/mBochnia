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

![signal-2025-10-15-170352_002](https://github.com/user-attachments/assets/5684c40d-d7b5-4adf-b4ba-9f38b3c6cb7e)
<img width="1907" height="926" alt="signal-2025-10-15-170407_002" src="https://github.com/user-attachments/assets/865b36fa-7cf9-4300-ae25-b9d6a35e8326" />
<img width="1907" height="926" alt="signal-2025-10-15-170435_002" src="https://github.com/user-attachments/assets/0d730c6b-ac3b-44d1-9b21-b6ac826938c7" />
