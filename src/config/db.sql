CREATE TABLE users (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` varchar(50),
    `firstname` varchar(50) NOT NULL,
    `lastname` varchar(50) NOT NULL,
    `username` varchar(50) NOT NULL,
    `email` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `avatar` varchar(255) NOT NULL,
    `role` ENUM('doctor','professor', 'admin') NOT NULL,
    `department` varchar(255) DEFAULT NULL,
    `institution` varchar(255) DEFAULT NULL,
    `education` text,
    `interests` text,
    `url` varchar(255) DEFAULT NULL
);

CREATE TABLE posts (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` varchar(255) NOT NULL,
    `description` text NOT NULL,
    `created` DATETIME NOT NULL DEFAULT current_timestamp(),
    `updated` DATETIME NOT NULL DEFAULT current_timestamp(),
    `user_id` int(11) UNSIGNED NOT NULL,
    `category` ENUM('problem', 'announcement') NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);


CREATE TABLE files (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `post_id` int(11) UNSIGNED NOT NULL,
    `user_id` int(11) UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `path` VARCHAR(1023) NOT NULL,
    `uploaded` DATETIME NOT NULL DEFAULT current_timestamp(),
    FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

CREATE TABLE comments (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` int(11) UNSIGNED NOT NULL,
    `post_id` int(11) UNSIGNED NOT NULL,
    `comment` text NOT NULL,
    `created` DATETIME NOT NULL DEFAULT current_timestamp(),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE    
);