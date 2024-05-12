CREATE TABLE `likes` (
    `article_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `created` datetime NOT NULL,
    PRIMARY KEY (`article_id`, `user_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
ALTER TABLE `likes`
ADD KEY `article_id` (`article_id`),
    ADD KEY `user_id` (`user_id`),
    ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;