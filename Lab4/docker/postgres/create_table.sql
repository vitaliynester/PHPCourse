CREATE TABLE IF NOT EXISTS "user" (
  id serial PRIMARY KEY,
  last_name varchar(255) not null,
  first_name varchar(255) not null,
  email varchar(255) not null,
  password varchar(255) not null
);