DROP TABLE IF EXISTS Posts; DROP TABLE IF EXISTS Users;

CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    PasswordHash VARCHAR(256) NOT NULL, 
    Email VARCHAR(100) NOT NULL UNIQUE,
    PostAmount INT DEFAULT 0,
    AccountCreationDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Image VARCHAR(255) DEFAULT  './storage/default.jpg'
);

CREATE TABLE Posts (
    PostID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    ContentText TEXT,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

SELECT * FROM Brandon200547547.Users;
SELECT * FROM Brandon200547547.Posts;

INSERT INTO Users (Username, PasswordHash, Email, PostAmount, AccountCreationDate)
VALUES
    ('user1', 'password1_hashed', 'user1@example.com', 0, CURRENT_TIMESTAMP),
    ('user2', 'password2_hashed', 'user2@example.com', 0, CURRENT_TIMESTAMP),
    ('user3', 'password3_hashed', 'user3@example.com', 0, CURRENT_TIMESTAMP),
    ('user4', 'password4_hashed', 'user4@example.com', 0, CURRENT_TIMESTAMP),
    ('user5', 'password5_hashed', 'user5@example.com', 0, CURRENT_TIMESTAMP),
    ('user6', 'password6_hashed', 'user6@example.com', 0, CURRENT_TIMESTAMP),
    ('user7', 'password7_hashed', 'user7@example.com', 0, CURRENT_TIMESTAMP),
    ('user8', 'password8_hashed', 'user8@example.com', 0, CURRENT_TIMESTAMP),
    ('user9', 'password9_hashed', 'user9@example.com', 0, CURRENT_TIMESTAMP),
    ('user10', 'password10_hashed', 'user10@example.com', 0, CURRENT_TIMESTAMP),
    ('user11', 'password11_hashed', 'user11@example.com', 0, CURRENT_TIMESTAMP),
    ('user12', 'password12_hashed', 'user12@example.com', 0, CURRENT_TIMESTAMP),
    ('user13', 'password13_hashed', 'user13@example.com', 0, CURRENT_TIMESTAMP),
    ('user14', 'password14_hashed', 'user14@example.com', 0, CURRENT_TIMESTAMP);