CREATE DATABASE BloodConnect;

CREATE TABLE BloodTypes (
  blood_type_id INT PRIMARY KEY AUTO_INCREMENT, 
  abo_group ENUM('A', 'B', 'AB', 'O') NOT NULL,   
  rh_factor ENUM('+', '-') NOT NULL,           
  description TEXT                      
  /* FDs: blood_type_id -> {abo_group, rh_factor, description} */
);

CREATE TABLE Donors (
  donor_id INT PRIMARY KEY AUTO_INCREMENT,  
  first_name VARCHAR(50) NOT NULL,          
  last_name VARCHAR(50) NOT NULL,           
  date_of_birth DATE NOT NULL,               
  address VARCHAR(255) NOT NULL,             
  phone_number VARCHAR(20),                 
  email_address VARCHAR(100) UNIQUE,        
  password VARCHAR(255) NOT NULL,  -- Added password attribute
  blood_type_id INT,                         
  abo_group ENUM('A+', 'B+', 'AB+', 'O+','A-', 'B-', 'AB-', 'O-'),  
  /* FDs: donor_id -> {first_name, last_name, date_of_birth, address, phone_number, email_address, password} */    
  FOREIGN KEY (blood_type_id) REFERENCES BloodTypes(blood_type_id)  
);

CREATE TABLE Hospitals (
  hospital_id INT PRIMARY KEY NOT NULL,
  name VARCHAR(100) NOT NULL,
  address VARCHAR(255) NOT NULL,
  phone_number VARCHAR(20),
  email_address VARCHAR(100) UNIQUE,
  password VARCHAR(255) NOT NULL,  -- Added password attribute
  blood_type_id INT,
  /* FDs: hospital_id -> {name, address, phone_number, email_address, password} */
  FOREIGN KEY (blood_type_id) REFERENCES BloodTypes(blood_type_id)
);

CREATE TABLE Recipients (
  recipient_id INT PRIMARY KEY AUTO_INCREMENT, 
  first_name VARCHAR(50) NOT NULL,          
  last_name VARCHAR(50) NOT NULL,
  blood_type_id INT NOT NULL,                     
  medical_condition_category VARCHAR(50) NOT NULL,  
  urgency_level INT NOT NULL,
  email_address VARCHAR(100) ,        
  address VARCHAR(255) NOT NULL,
  phone_number VARCHAR(20),
  additional_info TEXT,
  blood_group ENUM('A+', 'B+', 'AB+', 'O+','A-', 'B-', 'AB-', 'O-'), 
  request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  /* FDs: recipient_id -> {blood_type_id, medical_condition_category, urgency_level, email_address, password} */
  FOREIGN KEY (blood_type_id) REFERENCES BloodTypes(blood_type_id)
    ON DELETE RESTRICT  
    ON UPDATE CASCADE   
);

CREATE TABLE BloodInventory (
  blood_unit_id INT PRIMARY KEY AUTO_INCREMENT,  
  donor_id INT NOT NULL,                         
  blood_type_id INT NOT NULL,                     
  collected_date DATE NOT NULL,                   
  expiry_date DATE NOT NULL,                      
  storage_location VARCHAR(100) NOT NULL,         
  /* FDs: blood_unit_id -> {donor_id, blood_type_id, collected_date, expiry_date, storage_location} */
  FOREIGN KEY (donor_id) REFERENCES Donors(donor_id)  
    ON DELETE RESTRICT  
    ON UPDATE CASCADE,  
  FOREIGN KEY (blood_type_id) REFERENCES BloodTypes(blood_type_id)
    ON DELETE RESTRICT  
    ON UPDATE CASCADE   
);

CREATE TABLE Donations (
  donation_id INT PRIMARY KEY AUTO_INCREMENT,   
  donor_id INT NOT NULL,                         
  donation_date DATE NOT NULL,                   
  blood_volume INT NOT NULL,                      
  hemoglobin_level FLOAT NOT NULL,                
  /* FDs: donation_id -> {donor_id, donation_date, blood_volume, hemoglobin_level} */
  FOREIGN KEY (donor_id) REFERENCES Donors(donor_id)  
    ON DELETE RESTRICT  
    ON UPDATE CASCADE   
);




