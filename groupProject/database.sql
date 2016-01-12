DROP TABLE IF EXISTS group19.authentication;
DROP TABLE IF EXISTS group19.user_info;

DROP SCHEMA IF EXISTS group19;

CREATE SCHEMA group19;


/* Table name: accounts
attribute list:
-username: varchar(25) - the username of an account. Primary key, can not be null.
-password: varchar(64) - a SHA256 hash of the user's password, hashed USING PHP. Can not be null.
-salt: varchar(64) - a SHA256 hash of the concatinated username, password, and hashed password. Use the code $salt = hash(($username.$password.hash($password)));. Can not be null.
 */

CREATE TABLE authentication(
	username varchar(25) PRIMARY KEY,
	password_hash varchar(64) NOT NULL,
	salt varchar(64) NOT NULL
	);
	
/* Table name: user_picks
attribute list:
-username: varchar(25) - the username of an account. Foreign key, can not be null.
-week_no: Integer - the week number of the game. Can not be null.
-game_no: Integer - the number of the game in that week. Can not be null.
-pick: varchar(40) - the name of the team chosen. Can not be null.
PRIMARY KEY: composite(username, week_no, game_no) 
 */
 
 CREATE TABLE user_picks(
	username varchar(25) REFERENCES accounts(username) ON DELETE CASCADE ON UPDATE CASCADE ,
	week_no INTEGER NOT NULL,
	game_no INTEGER NOT NULL,
	pick varchar(40) NOT NULL,
	PRIMARY KEY (username, week_no, game_no)
	);
	
/* Table name: user_record
attribute list:
-username: varchar(25) - the username of an account. Foreign key, can not be null.
-week_no: Integer - the week number of the game. Can not be null.
-correct_picks_week: Integer - the number of correct picks in that week. Can not be null.
-wrong_picks_week: Integer - the number of wrong picks in that week. Can not be null.
*/
 
 CREATE TABLE user_record(
	username varchar(25) REFERENCES accounts(username) ON DELETE CASCADE ON UPDATE CASCADE ,
	week_no INTEGER NOT NULL,
	correct_picks_week INTEGER NOT NULL DEFAULT 0,
	wrong_picks_week INTEGER NOT NULL DEFAULT 0,
	PRIMARY KEY (username, week_no)
	);


/* Table name: user_record_overall
attribute list:
-username: varchar(25) - the username of an account. Foreign key, can not be null.
-correct_picks: Integer - the number of correct picks total. Can not be null.
-wrong_picks: Integer - the number of wrong picks total. Can not be null.
PRIMARY KEY: composite(username, week_no, game_no) 
 */	
CREATE TABLE user_info(
	username varchar(25) REFERENCES accounts(username) ON DELETE CASCADE ON UPDATE CASCADE ,
	correct_picks INTEGER NOT NULL DEFAULT 0,
	wrong_picks INTEGER NOT NULL DEFAULT 0,
	PRIMARY KEY (username)
	);


/* Table name: weekly_stats
attribute list:
-week_no: Integer - the week number of the game. Can not be null.
-game_no: Integer - the number of the game in that week. Can not be null.
-home: varchar(40) - the home team. Can not be null.
-away: varchar(40) - the away team. Can not be null.
-spread: Float - the spread of the game, based on home team. Can not be null.
-winner: varchar(40) - The winner of the game. Null by default.
PRIMARY KEY: composite(week_no, game_no) 
 */	
CREATE TABLE weekly_stats(
	week_no INTEGER NOT NULL,
	game_no INTEGER NOT NULL,
	home varchar(40) REFERENCES team(name),
	away varchar(40) REFERENCES team(name),
	spread FLOAT NOT NULL DEFAULT 0,
	winner varchar(40),
	PRIMARY KEY (week_no, game_no)
	);

	
/* Table name: team
attribute list:
-name: varchar(40) - the team. Primary key, can not be null.
-wins: Integer - the total number of home team's wins
-losses: Integer - the total number of home team's losses	
*/
CREATE TABLE team(
	name varchar(40) PRIMARY KEY,
	wins INTEGER NOT NULL DEFAULT 0,
	losses INTEGER NOT NULL DEFAULT 0,
	);
	
/* Table name: groups
attribute list:
-group_name: varchar(40) - the group name, as defined by the owner. Primary key, can not be null.
-owner: varchar(40) - the username of the owner. This is not linked to the actual owner's account, so the group is not disbanded if the owner leaves. Can not be null.
-average: float - the average win/loss ratio of the team, as the average of each user's win/loss ratio.
*/
CREATE TABLE groups(
	group_name varchar(40) PRIMARY KEY,
	owner varchar(40) NOT NULL,
	average FLOAT DEFAULT 0
	);
	
/* Table name: group_users (many to many relational table)
attribute list:
-group_name: varchar(40) - the group name. Foreign key, can not be null.
-username: varchar(40) - the username of a member of the group. Foreign key, can not be null.
*/
CREATE TABLE group_users(
	group_name varchar(40) REFERENCES groups(group_name),
	username varchar(40) REFERENCES accounts(username)
	);