BEGIN;

ALTER TABLE `layer` ADD `editable` BOOLEAN NOT NULL DEFAULT TRUE;

COMMIT;