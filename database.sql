CREATE DATABASE facebook;

use facebook;

CREATE TABLE User 
(
	id int(11) PRIMARY KEY AUTO_INCREMENT,
	name varchar(100) NOT NULL,
	dob date,
	password varchar(8) NOT NULL,
	email_id varchar(100) NOT NULL UNIQUE,
	phone bigint(18) NOT NULL UNIQUE,
	about varchar(10000),
	photo varchar(255) DEFAULT 'default.jpg',
	cover_photo varchar(255) DEFAULT 'default_cover.jpg'
);

CREATE TABLE friends(
	person1 int,
	person2 int,
	PRIMARY KEY (person1, person2),
	FOREIGN KEY (person1) REFERENCES User(id) ON DELETE CASCADE,
	FOREIGN KEY (person2) REFERENCES User(id) ON DELETE CASCADE
);

CREATE TABLE Post (
	id int NOT NULL AUTO_INCREMENT,
	DOC datetime DEFAULT CURRENT_TIMESTAMP, 
	user_id int,
	likes int default 0,
	shares int default 0,
	content text,
	image varchar(255),
	primary key (id),
	foreign key (user_id) references User(id) ON DELETE CASCADE
);



CREATE TABLE Photo (
	id INT AUTO_INCREMENT PRIMARY KEY,
	user_id INT NOT NULL,
	image varchar(255) NOT NULL,
	foreign key (user_id) references User(id) ON DELETE CASCADE
);

CREATE TABLE Reel (
	id INT AUTO_INCREMENT PRIMARY KEY,
	user_id INT NOT NULL,
	reel varchar(255) NOT NULL,
	foreign key (user_id) references User(id) ON DELETE CASCADE
);


CREATE TABLE Likes (
	user_id INT NOT NULL,
	post_id INT NOT NULL,
	PRIMARY KEY(user_id, post_id),
	FOREIGN KEY (user_id) references User(id) ON DELETE CASCADE,
	FOREIGN KEY (post_id) references Post(id) ON DELETE CASCADE
);

CREATE TABLE Comments (
	id int AUTO_INCREMENT PRIMARY KEY, 
	user_id INT NOT NULL,
	post_id INT NOT NULL,
	comment TEXT NOT NULL,
	created DATETIME DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (user_id) references User(id) ON DELETE CASCADE,
	FOREIGN KEY (post_id) references Post(id) ON DELETE CASCADE
);

INSERT INTO friends (person1, person2) VALUES
(1, 2), -- John and Jane are friends
(1, 3), -- John and Mike are friends
(2, 4), -- Jane and Rachel are friends
(3, 4), -- Mike and Rachel are friends
(4, 5); -- Rachel and Harvey are friends

INSERT INTO Photo (user_id, image) VALUES
(2, 'photos_default.jpg'),
(5, 'photos_default.jpg'),
(1, 'photos_default.jpg');

INSERT INTO Reel (user_id, reel) VALUES
(2, 'default_reel.jpg'),
(5, 'default_reel.jpg'),
(1, 'default_reel.jpg');

INSERT INTO Post (user_id, likes, shares, content, image) VALUES
(1, 5, 1, 'Having a great day!', 'post1.jpg'),
(2, 2, 0, 'Check out this sunset view.', 'sunset.jpg'),
(3, 10, 3, 'Just finished my new project.', NULL);

INSERT INTO Likes (user_id, post_id) VALUES
(2, 1), -- Jane liked John's post (post_id 1)
(3, 1), -- Mike liked John's post (post_id 1)
(4, 2), -- Rachel liked Jane's post (post_id 2)
(1, 3); -- John liked Mike's post (post_id 3)


INSERT INTO Comments (user_id, post_id, comment) VALUES
(2, 1, 'Welcome to the platform, John!'),
(4, 2, 'Wow, that layout looks so clean!'),
(1, 3, 'Check out the official documentation, it helped me a lot.'),
(5, 3, 'Get it done. Don''t look for shortcuts.');

