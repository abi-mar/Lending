ALTER TABLE `scheduled_payment` ADD `weekno` INT NOT NULL AFTER `row_id`;

-- set proper weekno for existing scheduled_payment data
CREATE TEMPORARY TABLE temp_scheduled_payment AS
SELECT 
    row_id, 
    loan_record_row_id, 
    scheduled_date, 
    ROW_NUMBER() OVER (PARTITION BY loan_record_row_id ORDER BY scheduled_date) AS weekno
FROM 
    scheduled_payment;
    
UPDATE scheduled_payment sp
JOIN temp_scheduled_payment tsp ON sp.row_id = tsp.row_id
SET sp.weekno = tsp.weekno;    