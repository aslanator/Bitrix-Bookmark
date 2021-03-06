CREATE TABLE `bookmark` (
        `ID` INT(11) NOT NULL AUTO_INCREMENT,
        `FAVICON` INT,
        `URL` TEXT NOT NULL,
        `TITLE` VARCHAR(255) NOT NULL,
        `META_DESCRIPTION` TEXT,
        `META_KEYWORDS` TEXT,
        `PASSWORD` VARCHAR(255),
        `CREATED` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY(ID)
    ) ENGINE = InnoDB;