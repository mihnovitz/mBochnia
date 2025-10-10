In order to enter database container use following command:  

sudo docker exec -it postgres psql -U appuser -d appdb  
In in order to add admin user to database execute following query:  
INSERT INTO users (
    first_name,
    last_name,
    address,
    phone,
    email,
    password,
    is_admin
) VALUES (
    'FIRST_NAME',
    'LAST_NAME',
    'ADDRESS',
    'PHONE_NUMBER',
    'EMAIL@BOCHNIA.CITY',
    crypt('PASSWORD', gen_salt('bf')),
    TRUE
);  
Following extension is required in order to execute query above:  
CREATE EXTENSION IF NOT EXISTS pgcrypto;

# Database Operations

## Database Container Access

```bash
sudo docker exec -it postgres psql -U appuser -d appdb
```

## Creating Admin user

```bash
INSERT INTO users (first_name, last_name, address, phone, email, password, is_admin)
```
```bash
VALUES ('FIRST_NAME', 'LAST_NAME', 'ADDRESS', 'PHONE_NUMBER', 'EMAIL@BOCHNIA.CITY', crypt('PASSWORD', gen_salt('bf')), TRUE);
```
## Following extension must be present in order to add Admin to database
If it's not included simply execute following query before inserting Admin account to database:  

```bash
CREATE EXTENSION IF NOT EXISTS pgcrypto;
```
