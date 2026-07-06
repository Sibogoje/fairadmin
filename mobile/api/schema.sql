CREATE TABLE IF NOT EXISTS mobile_api_tokens (
  id INT AUTO_INCREMENT PRIMARY KEY,
  memberid VARCHAR(64) NOT NULL,
  token_hash CHAR(64) NOT NULL,
  device_name VARCHAR(255) NULL,
  expires_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_used_at DATETIME NULL,
  UNIQUE KEY uq_mobile_api_token_hash (token_hash),
  KEY idx_mobile_api_memberid (memberid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS mobile_security_questions (
  memberid VARCHAR(64) NOT NULL PRIMARY KEY,
  question1 VARCHAR(255) NOT NULL,
  answer1_hash VARCHAR(255) NOT NULL,
  question2 VARCHAR(255) NOT NULL,
  answer2_hash VARCHAR(255) NOT NULL,
  question3 VARCHAR(255) NOT NULL,
  answer3_hash VARCHAR(255) NOT NULL,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;