CREATE TABLE feedback (
   id serial PRIMARY KEY,
   first_name VARCHAR (100) NOT NULL,
   last_name VARCHAR (100) NOT NULL,
   patronymic VARCHAR (100),
   email VARCHAR (255) NOT NULL,
   phone VARCHAR (50) NOT NULL,
   content TEXT NOT NULL,
   created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);