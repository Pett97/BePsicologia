SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS states;

CREATE TABLE states (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

DROP TABLE IF EXISTS citys;

CREATE TABLE citys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    state_id INTEGER NOT NULL
);

DROP TABLE IF EXISTS insurances;

CREATE TABLE insurances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(250) NOT NULL,
    city_id INT NOT NULL
);

DROP TABLE IF EXISTS clients;

CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    insurance_id INTEGER NOT NULL,
    street_name VARCHAR(255) NOT NULL,
    number INT NOT NULL,
    city_id INT NOT NULL,
    FOREIGN KEY (city_id) REFERENCES citys(id),
    FOREIGN KEY (insurance_id) REFERENCES insurances(id)
);

DROP TABLE IF EXISTS appointments;
/* agendamentos */
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    psychologist_id INTEGER NOT NULL,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    client_id INT NOT NULL,
    FOREIGN KEY (psychologist_id) REFERENCES users(id),
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

DROP TABLE IF EXISTS fixed_schedules;
/* horarios fixo  */
CREATE TABLE fixed_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    psychologist_id INT NOT NULL,
    day_of_week INT NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    FOREIGN KEY (psychologist_id) REFERENCES users(id)
);

SET FOREIGN_KEY_CHECKS = 1;
