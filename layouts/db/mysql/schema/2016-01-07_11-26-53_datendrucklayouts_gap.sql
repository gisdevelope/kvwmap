BEGIN;

ALTER TABLE `datendrucklayouts` ADD `gap` INT NOT NULL DEFAULT 20;

COMMIT;
