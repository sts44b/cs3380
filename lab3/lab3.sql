DROP SCHEMA IF EXISTS lab3 CASCADE;

CREATE SCHEMA lab3;

CREATE TABLE lab3.patient (
        ssn SERIAL PRIMARY KEY,
        first_name varchar(50) NOT NULL,
        last_name varchar(50) NOT NULL);

CREATE TABLE lab3.insurance  (
        policy_num SERIAL PRIMARY KEY,
        insurer varchar(50),
        policy_owner REFERENCES patient(ssn));

CREATE TABLE lab3.condition (
        icd10 varchar(6) PRIMARY KEY,
        description varchar(500),
        affected_person integer REFERENCES patient(ssn));

CREATE TABLE lab3.labwork (
        test_name varchar(20),
        test_timestamp timestamp,
        test_value varchar(20),
        patient_id integer REFERENCES patient(ssn));

CREATE TABLE lab3.has_appointment (
        appt_time time,
        appt_date date,
        patient_id REFERENCES patient(ssn)
        doctor_id REFERENCES doctor(meidcal_licese_num)
        PRIMARY KEY (patient_id, doctor_id));

CREATE TABLE lab3.doctor (
        medical_license_num SERIAL PRIMARY KEY,
        first_name varchar(20),
        last_name varchar(20),
        location integer REFERENCES office(room_number));

CREATE TABLE lab3.office (
        room_number SERIAL PRIMARY KEY,
        waiting_room_capacity integer,
        address varchar REFERENCES building(address);

CREATE TABLE lab3.building (
        address varchar(50) PRIMARY KEY,
        name varchar(20),
        city varchar(20),
        state varchar(2),
        zipcode integer);

INSERT INTO lab3.patient VALUES(123456789, 'Mitch', 'Hedberg');
INSERT INTO lab3.patient VALUES(283675936, 'Robin', 'Williams');
INSERT INTO lab3.patient VALUES(398475895, 'George', 'Carlin');
INSERT INTO lab3.insurance VALUES(12345, 'Bluecross' , 123456789);
INSERT INTO lab3.insurance VALUES(54321, 'UMR' , 283675936);
INSERT INTO lab3.insurance VALUES(24351, 'Medicare' , 398475895);
INSERT INTO lab3.condition VALUES('T36-T50', 'overdose', 123456789);
INSERT INTO lab3.condition VALUES('F32.3', 'depression', 283675936);
INSERT INTO lab3.condition VALUES('I11.0', 'heart attack', 398475895);
INSERT INTO lab3.labwork VALUES('blood test', TO_TIMESTAMP(:ts_val, '2005-03-29 23:00:00'), 'positive', 123456789);
INSERT INTO lab3.labwork VALUES('urin test', TO_TIMESTAMP(:ts_val, '2014-08-11 23:00:00'), 'positive', 283675936);
INSERT INTO lab3.labwork VALUES('stress test', TO_TIMESTAMP(:ts_val, '2008-06-22 23:00:00'), 'positive', 398475895);
INSERT INTO lab3.has_appointment VALUES('03:00:00', 'YYYY-MM-DD', 123456789, 1);
INSERT INTO lab3.has_appointment VALUES('04:00:00', 'YYYY-MM-DD', 283675936, 2);
INSERT INTO lab3.has_appointment VALUES('05:00:00', 'YYYY-MM-DD', 398475895, 3);
INSERT INTO lab3.doctor VALUES(1, 'Bob', 'Smith', 123);
INSERT INTO lab3.doctor VALUES(2, 'John', 'Smith', 124);
INSERT INTO lab3.doctor VALUES(3, 'Tom', 'Smith', 125);
INSERT INTO lab3.office VALUES(123, 20, '123 Fake Street');
INSERT INTO lab3.office VALUES(124, 20, '123 Fake Street');
INSERT INTO lab3.office VALUES(125, 20, '123 Fake Street');
INSERT INTO lab3.building VALUES('123 Fake Street', 'Mercy', 'St. Louis', 'MO', 63026);
INSERT INTO lab3.building VALUES('123 Real Street', 'BJC', 'St. Louis', 'MO', 63026);
INSERT INTO lab3.building VALUES('122 Fake Street', 'St. Lukes', 'St. Louis', 'MO', 63026);



