CREATE DATABASE chatbot_db;
USE chatbot_db;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-----------
ALTER TABLE users
ADD COLUMN role ENUM('user', 'admin', 'manager') NOT NULL DEFAULT 'user';
-------------
ALTER TABLE `users` ADD `phone` INT(15) NOT NULL AFTER `email`, ADD `website` VARCHAR(255) NOT NULL AFTER `phone`;
-----------------------------------------------
CREATE TABLE chatbot_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    chatbot_id INT NOT NULL,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Foreign key linking to users table
    CONSTRAINT fk_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    -- Foreign key linking to option_trees table (used as chatbot_id)
    CONSTRAINT fk_chatbot
        FOREIGN KEY (chatbot_id)
        REFERENCES option_trees(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);







[
  {
    PARENT: "2",
    children: [
      {
        option: "2.1",
        children: ["2.111111", "2.22222222"]
      },
      {
        option: "2.2",
        children: []
      }
    ]
  },
  {
    PARENT: "1",
    children: [
      {
        option: "1.1",
        children: ["1.1111111"]
      }
    ]
  }
]
