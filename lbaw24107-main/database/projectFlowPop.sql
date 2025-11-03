DROP SCHEMA IF EXISTS lbaw24101 CASCADE;
CREATE SCHEMA lbaw24101;
SET search_path TO lbaw24101;


-- Drop all tables to avoid conflicts
DROP TABLE IF EXISTS Admin_Change CASCADE;
DROP TABLE IF EXISTS Admin CASCADE;
DROP TABLE IF EXISTS Project_Member CASCADE;
DROP TABLE IF EXISTS Message CASCADE;
DROP TABLE IF EXISTS Task_Label CASCADE;
DROP TABLE IF EXISTS Task CASCADE;
DROP TABLE IF EXISTS Task_Comment CASCADE;
DROP TABLE IF EXISTS Project CASCADE;
DROP TABLE IF EXISTS Project_Category CASCADE;
DROP TABLE IF EXISTS Notification CASCADE;
DROP TABLE IF EXISTS User_Settings CASCADE;
--DROP TABLE IF EXISTS Utilizador CASCADE;
DROP TABLE IF EXISTS Account CASCADE;
DROP TABLE IF EXISTS Task_Responsible CASCADE;
DROP TABLE IF EXISTS password_reset_tokens CASCADE;

-- Account Table
CREATE TABLE Account (
    id SERIAL PRIMARY KEY,
    username TEXT NOT NULL UNIQUE CHECK(LENGTH(username) <= 30),
    password TEXT,
    google_id TEXT,
    email TEXT NOT NULL UNIQUE,
    is_admin BOOLEAN DEFAULT FALSE NOT NULL,
    biography TEXT CHECK(LENGTH(biography) <= 250),
    phone_number TEXT CHECK(LENGTH(phone_number) >= 9 AND LENGTH(phone_number) <= 15),
    status TEXT CHECK(LENGTH(status) <= 50),
    profile_picture TEXT,
    remember_token TEXT
);

-- Utilizador Table
/*
CREATE TABLE Utilizador (
    id SERIAL PRIMARY KEY,
    profile_picture_id INT UNIQUE,
    biography TEXT CHECK(LENGTH(biography) <= 250),
    phone_number TEXT UNIQUE CHECK(LENGTH(phone_number) >= 10 AND LENGTH(phone_number) <= 15),
    status TEXT CHECK(LENGTH(status) <= 50),
    account_id INT NOT NULL,
    FOREIGN KEY (account_id) REFERENCES Account(id)
);
*/


-- User_Settings Table
CREATE TABLE User_Settings (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    dark_mode BOOLEAN DEFAULT FALSE NOT NULL,
    task_notifications BOOLEAN DEFAULT TRUE NOT NULL,
    project_notifications BOOLEAN DEFAULT TRUE NOT NULL,
    forum_message_notifications BOOLEAN DEFAULT FALSE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Account(id)
);

-- Project_Category Table
CREATE TABLE Project_Category (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL CHECK(LENGTH(name) <= 30)
);

-- Project Table
CREATE TABLE Project (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL CHECK(LENGTH(name) <= 50),
    description TEXT CHECK(LENGTH(description) <= 250),
    status TEXT CHECK(status IN ('ongoing', 'archived', 'complete')),
    project_category_id INT NOT NULL,
    FOREIGN KEY (project_category_id) REFERENCES Project_Category(id)
);

-- Project_Member Table
CREATE TABLE Project_Member (
    user_id INT NOT NULL,
    project_id INT NOT NULL,
    role TEXT CHECK(role IN ('Project Coordinator', 'Project Member')),
    favorite BOOLEAN DEFAULT FALSE NOT NULL,
    invite_status TEXT CHECK(invite_status IN ('pending', 'accepted')),
    PRIMARY KEY (user_id, project_id),
    FOREIGN KEY (user_id) REFERENCES Account(id),
    FOREIGN KEY (project_id) REFERENCES Project(id)
);


-- Task Table
CREATE TABLE Task (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL CHECK(LENGTH(name) <= 50),
    description TEXT CHECK(LENGTH(description) <= 250),
    due_date DATE CHECK(due_date > CURRENT_DATE),
    priority INT CHECK(priority > 0),
    status TEXT,
    project_id INT NOT NULL,
    creator_id INT,
    FOREIGN KEY (project_id) REFERENCES Project(id),
    FOREIGN KEY (creator_id) REFERENCES Account(id)
);

CREATE TABLE Task_Responsible (
    task_id INT NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY (task_id, user_id),
    FOREIGN KEY (task_id) REFERENCES Task(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Account(id) ON DELETE CASCADE
);

-- Task_Label Table
CREATE TABLE Task_Label (
    id SERIAL PRIMARY KEY,
    name TEXT CHECK(LENGTH(name) <= 30)
);

-- Task_Comment Table
CREATE TABLE Task_Comment (
    id SERIAL PRIMARY KEY,
    comment_text TEXT NOT NULL CHECK(LENGTH(comment_text) <= 250),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    task_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Account(id),
    FOREIGN KEY (task_id) REFERENCES Task(id)
);

-- Message Table
CREATE TABLE Message (
    id SERIAL PRIMARY KEY,
    message_text TEXT NOT NULL CHECK(LENGTH(message_text) <= 250),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    project_id INT NOT NULL,
    parent_id INT,
    FOREIGN KEY (user_id) REFERENCES Account(id),
    FOREIGN KEY (project_id) REFERENCES Project(id),
    FOREIGN KEY (parent_id) REFERENCES Message(id)
);

-- Notification Table
CREATE TABLE Notification (
    id SERIAL PRIMARY KEY,
    notification_text TEXT NOT NULL CHECK(LENGTH(notification_text) <= 250),
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    project_id INT NOT NULL,
    response TEXT,
    read_status BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notification_type TEXT CHECK(notification_type IN ('invitation', 'message', 'task', 'project')),
    FOREIGN KEY (sender_id) REFERENCES Account(id),
    FOREIGN KEY (receiver_id) REFERENCES Account(id),
    FOREIGN KEY (project_id) REFERENCES Project(id)
);

-- Admin Table
CREATE TABLE Admin (
    id SERIAL PRIMARY KEY,
    account_id INT NOT NULL,
    FOREIGN KEY (account_id) REFERENCES Account(id)
);

-- Admin_Change Table
CREATE TABLE Admin_Change (
    id SERIAL PRIMARY KEY,
    timestamp TIMESTAMP CHECK(timestamp <= CURRENT_TIMESTAMP),
    change_log TEXT CHECK(LENGTH(change_log) <= 100),
    admin_id INT NOT NULL,
    FOREIGN KEY (admin_id) REFERENCES Admin(id)
);

CREATE TABLE password_reset_tokens (
    email Text NOT NULL PRIMARY KEY,
    token TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert into Account table
INSERT INTO Account (username, password, email, biography, phone_number, status, profile_picture, remember_token,is_admin)
VALUES 
('john_doe', '$2y$10$XUpjNKJcQvz4O2oA4AYUK.AnDkCIDzpm57LEXrP1KTmb/0LRMw4GO', 'john.doe@example.com', 'Software engineer.', '123456789', 'active', 'profile1.jpg', 'remembertoken1', FALSE),
('jane_doe', '$2y$10$XUpjNKJcQvz4O2oA4AYUK.AnDkCIDzpm57LEXrP1KTmb/0LRMw4GO', 'jane.doe@example.com', 'Project manager.', '987654321', 'active', 'profile2.jpg', 'remembertoken2',FALSE),
('admin_user', '$2y$10$XUpjNKJcQvz4O2oA4AYUK.AnDkCIDzpm57LEXrP1KTmb/0LRMw4GO', 'admin@example.com', NULL, NULL, 'admin', NULL, NULL,TRUE),
('alex_smith', '$2y$10$XUpjNKJcQvz4O2oA4AYUK.AnDkCIDzpm57LEXrP1KTmb/0LRMw4GO', 'alex.smith@example.com', 'Graphic designer.', '111222333', 'active', 'profile3.jpg', 'remembertoken3', FALSE),
('lisa_brown', '$2y$10$XUpjNKJcQvz4O2oA4AYUK.AnDkCIDzpm57LEXrP1KTmb/0LRMw4GO', 'lisa.brown@example.com', 'Data analyst.', '444555666', 'active', 'profile4.jpg', 'remembertoken4', FALSE),
('mark_jones', '$2y$10$XUpjNKJcQvz4O2oA4AYUK.AnDkCIDzpm57LEXrP1KTmb/0LRMw4GO', 'mark.jones@example.com', NULL, '777888999', 'inactive', 'profile5.jpg', 'remembertoken5', FALSE),
('deleted_user', '$2y$10$XUpjNKJcQvz4O2oA4AYUK.AnDkCIDzpm57LEXrP1KTmb/0LRMw4GO', 'Email redacted' , NULL, NULL, 'deleted', NULL, NULL, FALSE);


-- Insert into User_Settings table
INSERT INTO User_Settings (user_id, dark_mode, task_notifications, project_notifications, forum_message_notifications)
VALUES
(1, TRUE, TRUE, TRUE, FALSE),
(2, FALSE, TRUE, FALSE, TRUE),
(3, TRUE, FALSE, FALSE, FALSE);

-- Insert into Project_Category table
INSERT INTO Project_Category (name)
VALUES 
('Technology'),
('Healthcare'),
('Education');

-- Insert into Project table
INSERT INTO Project (name, description, status, project_category_id)
VALUES
('Tech Project 1', 'A cutting-edge technology project.', 'ongoing', 1),
('Healthcare Initiative', 'Improving healthcare systems.', 'ongoing', 2),
('Education Reform', 'Enhancing education accessibility.', 'complete', 3),
('AI Development', 'Developing AI solutions.', 'ongoing', 1),
('Mental Health App', 'Building a mental health support app.', 'ongoing', 2),
('Online Course Platform', 'Creating an online learning platform.', 'ongoing', 3),
('Green Energy Initiative', 'Advancing renewable energy projects.', 'complete', 1);



-- Insert into Project_Member table
INSERT INTO Project_Member (user_id, project_id, role, favorite, invite_status)
VALUES
(1, 1, 'Project Coordinator', TRUE, 'accepted'),
(1, 2, 'Project Member', FALSE, 'pending'),
(2, 1, 'Project Member', TRUE, 'accepted'),
(2, 3, 'Project Member', FALSE, 'accepted'),
(3, 3, 'Project Coordinator', TRUE, 'accepted');

-- Insert into Task table
INSERT INTO Task (name, description, due_date, priority, status, project_id)
VALUES
('Develop feature X', 'Implement feature X for the project.', '2025-3-01', 1, 'in-progress', 1),
('Write documentation', 'Complete the documentation.', '2025-11-30', 2, 'pending', 1),
('Healthcare research', 'Conduct research for healthcare initiative.', '2025-12-15', 3, 'complete', 2),
('Create UI Mockups', 'Design UI for the mental health app.', '2025-02-01', 1, 'in-progress', 2),
('Write AI Model', 'Develop the AI model for the project.', '2025-03-15', 2, 'pending', 4),
('Update Documentation', 'Add recent changes to the documentation.', '2025-04-20', 3, 'pending', 3),
('Finalize Budget', 'Plan and finalize the project budget.', '2025-05-10', 1, 'pending', 4);

-- Insert into Task_Label table
INSERT INTO Task_Label (name)
VALUES
('High Priority'),
('Bug Fix'),
('Feature');

-- Insert into Task_Comment table
INSERT INTO Task_Comment (comment_text, user_id, task_id, created_at, updated_at)
VALUES
('Great progress on this task!', 1, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Need to address this issue.', 2, 1, '2024-11-22 14:00:00', '2024-11-22 14:00:00'),
('Task completed successfully.', 3, 3, '2024-11-23 11:00:00', '2024-11-23 11:00:00');

-- Insert into Message table
INSERT INTO Message (message_text, created_at, updated_at, user_id, project_id)
VALUES
('Let’s meet tomorrow to discuss progress.', '2024-11-22 15:00:00', '2024-11-22 15:00:00', 1, 1),
('Update on feature X has been uploaded.', '2024-11-22 16:00:00', '2024-11-22 16:00:00', 2, 1),
('Project milestone achieved!', '2024-11-23 10:00:00', '2024-11-23 10:00:00', 3, 3);

-- Insert into Admin table
INSERT INTO Admin (account_id)
VALUES
(3);

-- Insert into Admin_Change table
INSERT INTO Admin_Change (timestamp, change_log, admin_id)
VALUES
('2024-11-23 09:00:00', 'Added a new user account.', 1),
('2024-11-23 10:00:00', 'Updated project settings.', 1);
