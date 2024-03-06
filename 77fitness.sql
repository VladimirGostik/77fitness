-- Create 'admins' table
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Create 'articles' table
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT
);

-- Create 'clients' table
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    phone_number VARCHAR(20)
);

-- Create 'groups' table
CREATE TABLE groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trainer_id INT,
    room_id INT,
    max_participants INT NOT NULL,
    is_reserved BOOLEAN DEFAULT 0,
    FOREIGN KEY (trainer_id) REFERENCES trainers(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

-- Create 'group_participants' table
CREATE TABLE group_participants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT,
    client_id INT,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

-- Create 'payments' table
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date DATE NOT NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

-- Create 'reservations' table
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    trainer_id INT,
    start_reservation DATETIME,
    end_reservation DATETIME,
    reservation_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (trainer_id) REFERENCES trainers(id) ON DELETE CASCADE
);

-- Create 'rooms' table
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    capacity INT
);

-- Create 'trainers' table
CREATE TABLE trainers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    specialization VARCHAR(255) NOT NULL,
    description TEXT,
    experience TEXT,
    session_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create 'users' table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    phone_number VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    receive_notifications BOOLEAN DEFAULT 0,
    role INT DEFAULT 1
);

permamentky,
ked budem chciet urobit group rezervaciu, aby mohol aj jeden typek prihlasit viacerych
kredit system...
info o kazdej platbe
