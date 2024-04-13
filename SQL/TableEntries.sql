-- Inserting into BloodTypes
INSERT INTO BloodTypes (abo_group, rh_factor, description) VALUES
('A', '+', 'Universal donor'),
('B', '+', 'Universal recipient'),
('AB', '+', 'Rare blood type'),
('O', '+', 'Common blood type'),
('A', '-', 'Compatible with A- and AB-'),
('B', '-', 'Compatible with B- and AB-'),
('AB', '-', 'Compatible with AB-'),
('O', '-', 'Universal donor');

-- Inserting into Donors
INSERT INTO Donors (first_name, last_name, date_of_birth, address, phone_number, email_address, password, blood_type_id, abo_group) VALUES
('John', 'Doe', '1990-05-15', '123 Main St', '123-456-7890', 'john@example.com', 'password123', 1, 'A'),
('Jane', 'Smith', '1985-08-20', '456 Elm St', '987-654-3210', 'jane@example.com', 'password456', 2, 'B'),
('David', 'Johnson', '1978-11-10', '789 Oak St', '567-890-1234', 'david@example.com', 'password789', 3, 'AB'),
('Emily', 'Williams', '1995-02-25', '101 Pine St', '321-654-0987', 'emily@example.com', 'passwordABC', 4, 'O'),
('Michael', 'Brown', '1980-09-05', '202 Cedar St', '555-123-4567', 'michael@example.com', 'passwordXYZ', 5, 'A');

-- Inserting into Hospitals
INSERT INTO Hospitals (name, address, phone_number, email_address, password, blood_type_id, abo_group) VALUES
('City Hospital', '123 Hospital St', '555-987-6543', 'info@cityhospital.com', 'hospital123', 4, 'O'),
('County Medical Center', '456 Medical St', '444-222-3333', 'info@countymedicalcenter.com', 'hospital456', 2, 'B'),
('Regional Medical Center', '789 Health St', '111-333-5555', 'info@regionalmedicalcenter.com', 'hospital789', 1, 'A'),
('Community Hospital', '101 Community Ave', '777-888-9999', 'info@communityhospital.com', 'hospitalABC', 3, 'AB'),
('University Hospital', '202 University Dr', '222-444-6666', 'info@universityhospital.com', 'hospitalXYZ', 5, 'A');

-- Inserting into Recipients
INSERT INTO Recipients (blood_type_id, medical_condition_category, urgency_level, email_address, password) VALUES
(1, 'Emergency', 1, 'recipient1@example.com', 'recipient123'),
(2, 'Surgery', 2, 'recipient2@example.com', 'recipient456'),
(3, 'Chronic condition', 3, 'recipient3@example.com', 'recipient789'),
(4, 'Emergency', 1, 'recipient4@example.com', 'recipientABC'),
(5, 'Surgery', 2, 'recipient5@example.com', 'recipientXYZ');

-- Inserting into BloodInventory
INSERT INTO BloodInventory (donor_id, blood_type_id, collected_date, expiry_date, storage_location) VALUES
(1, 1, '2024-04-01', '2024-05-01', 'Hospital A'),
(2, 2, '2024-04-02', '2024-05-02', 'Hospital B'),
(3, 3, '2024-04-03', '2024-05-03', 'Hospital C'),
(4, 4, '2024-04-04', '2024-05-04', 'Hospital D'),
(5, 5, '2024-04-05', '2024-05-05', 'Hospital E');

-- Inserting into Donations
INSERT INTO Donations (donor_id, donation_date, blood_volume, hemoglobin_level) VALUES
(1, '2024-03-01', 500, 15.0),
(2, '2024-03-02', 600, 14.5),
(3, '2024-03-03', 700, 14.0),
(4, '2024-03-04', 800, 13.5),
(5, '2024-03-05', 900, 13.0);


UPDATE Donors SET rh_factor = '+' WHERE blood_type_id IN (1, 3, 5);
UPDATE Donors SET rh_factor = '-' WHERE blood_type_id IN (2, 4);


UPDATE Hospitals SET rh_factor = '+' WHERE blood_type_id IN (1, 3, 5);
UPDATE Hospitals SET rh_factor = '-' WHERE blood_type_id IN (2, 4);

UPDATE Recipients SET rh_factor = '+' WHERE blood_type_id IN (1, 3, 5);
UPDATE Recipients SET rh_factor = '-' WHERE blood_type_id IN (2, 4);


