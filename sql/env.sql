-- CREATE TABLE `env` (
--   `env_id` int(2) NOT NULL,
--   `env` char(2) NOT NULL,
--   `admin_id` int(5) NOT NULL,
--   PRIMARY KEY (`env_id`),
--   KEY `board_idfk_4` (`admin_id`),
--   CONSTRAINT `board_idfk_4` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON UPDATE CASCADE
-- );

-- INSERT INTO `env` ( `env`, `admin_id`) VALUES
-- ('通常', 1);
