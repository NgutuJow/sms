-- SQL script to add installment columns to fee_structures table
-- Run this in your MySQL database management tool (phpMyAdmin, MySQL Workbench, etc.)

USE sms; -- Replace 'sms' with your actual database name if different

ALTER TABLE fee_structures
ADD COLUMN allow_installments BOOLEAN DEFAULT FALSE,
ADD COLUMN number_of_installments INT DEFAULT 1,
ADD COLUMN installment_dates JSON NULL;

-- Verify the columns were added
DESCRIBE fee_structures;