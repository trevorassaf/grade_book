-- GSI Table
CREATE TABLE Gsis (
    id INT NOT NULL AUTO_INCREMENT,
    first_name varchar(30) NOT NULL,
    last_name varchar(30) NOT NULL,
    PRIMARY KEY(id)
);

-- Stat Set Table
CREATE TABLE StatSets (
    id INT NOT NULL AUTO_INCREMENT,
    num_exams INT NOT NULL,
    total_avg_score DOUBLE NOT NULL,
    total_std_dev DOUBLE NOT NULL,
    p1_avg DOUBLE NOT NULL,
    p2_avg DOUBLE NOT NULL,
    p3_avg DOUBLE NOT NULL,
    p4_avg DOUBLE NOT NULL,
    p5_avg DOUBLE NOT NULL,
    p1_std_dev DOUBLE NOT NULL,
    p2_std_dev DOUBLE NOT NULL,
    p3_std_dev DOUBLE NOT NULL,
    p4_std_dev DOUBLE NOT NULL,
    p5_std_dev DOUBLE NOT NULL,
    PRIMARY KEY(id)
);

-- Section Table
CREATE TABLE Sections (
    id INT NOT NULL AUTO_INCREMENT,
    gsi_id INT NOT NULL,
    section_number VARCHAR(20) NOT NULL,
    num_students INT NOT NULL,
    stat_set_id INT NOT NULL,
    PRIMARY KEY(id)
);

-- Exams Table
CREATE TABLE Exams (
    id INT NOT NULL AUTO_INCREMENT,
    section_id INT NOT NULL,
    p1_score INT NOT NULL,
    p2_score INT NOT NULL,
    p3_score INT NOT NULL,
    p4_score INT NOT NULL,
    p5_score INT NOT NULL,
    total_score INT NOT NULL,
    PRIMARY KEY(id)
);
