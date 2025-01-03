CREATE TABLE `collection` (
  `service_fee` decimal(15,2) NOT NULL,
  `notary` decimal(15,2) NOT NULL,
  `doc_stamp` decimal(15,2) NOT NULL,
  `interest` decimal(15,2) NOT NULL,
  `LRF` decimal(15,2) NOT NULL,
  `savings` decimal(15,2) NOT NULL,
  `damayan` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Accumulation of collections saved when loan is applied'


CREATE TABLE `customer` (
  `custno` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(20) DEFAULT NULL,
  `surname` varchar(40) DEFAULT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `mobileno` varchar(20) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `added_by` varchar(50) NOT NULL,
  `date_modified` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `balance` decimal(15,2) NOT NULL,
  PRIMARY KEY (`custno`),
  UNIQUE KEY `custno` (`custno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci


CREATE TABLE `loan_record` (
  `row_id` int(11) NOT NULL AUTO_INCREMENT,
  `custno` int(11) NOT NULL,
  `loan_date` date NOT NULL,
  `loan_amount` decimal(15,2) NOT NULL,
  `net_proceeds` decimal(15,2) NOT NULL,
  `amount_topay` decimal(15,2) NOT NULL,
  `balance` decimal(15,2) NOT NULL,
  `weekly_amortization` decimal(15,2) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `added_by` varchar(50) NOT NULL,
  PRIMARY KEY (`row_id`),
  UNIQUE KEY `row_id` (`row_id`),
  KEY `FK_customer_custno` (`custno`),
  CONSTRAINT `FK_customer_custno` FOREIGN KEY (`custno`) REFERENCES `customer` (`custno`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci	

CREATE TABLE `payment` (
  `row_id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` decimal(15,2) NOT NULL,
  `payment_date` date NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `added_by` varchar(50) NOT NULL,
  `loan_record_row_id` int(11) NOT NULL,
  PRIMARY KEY (`row_id`),
  KEY `FK_loan_record_row_id` (`loan_record_row_id`),
  CONSTRAINT `FK_loan_record_row_id` FOREIGN KEY (`loan_record_row_id`) REFERENCES `loan_record` (`row_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci	

CREATE TABLE `scheduled_payment` (
  `row_id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` decimal(15,2) NOT NULL,
  `date_paid` datetime DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL,
  `added_by` varchar(40) DEFAULT NULL,
  `is_paid` bit(2) NOT NULL COMMENT '0=not paid\r\n1=paid',
  `remaining_debt` decimal(15,2) DEFAULT NULL COMMENT 'For tracking excess or deficient payment',
  `loan_record_row_id` int(11) NOT NULL,
  PRIMARY KEY (`row_id`),
  KEY `FK_sp_loan_record_row_id` (`loan_record_row_id`),
  CONSTRAINT `FK_sp_loan_record_row_id` FOREIGN KEY (`loan_record_row_id`) REFERENCES `loan_record` (`row_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci	

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci	
