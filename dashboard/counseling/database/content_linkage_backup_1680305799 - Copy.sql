
CREATE TABLE `library_bookmarks` (
  `bookmark_id` int(255) NOT NULL AUTO_INCREMENT,
  `contentid` int(255) NOT NULL,
  `userid` int(255) NOT NULL,
  `timestamp` int(255) NOT NULL,
  PRIMARY KEY (`bookmark_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `library_list` (
  `list_id` int(255) NOT NULL AUTO_INCREMENT,
  `list_name` varchar(1000) NOT NULL,
  `list_type` varchar(1000) NOT NULL,
  `content_id` int(255) NOT NULL,
  `creator_userid` int(255) NOT NULL,
  `timestamp` int(255) NOT NULL,
  `last_update_timestamp` int(255) NOT NULL,
  PRIMARY KEY (`list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `library_contents` (
  `content_id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(1000) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `uploaderID` int(255) NOT NULL,
  `filename` varchar(1000) NOT NULL,
  `filetype` varchar(20) NOT NULL,
  `list_id` varchar(20) NOT NULL,
  `time` int(255) NOT NULL,
  PRIMARY KEY (`content_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4;

INSERT INTO library_contents VALUES("1","Shakespeare in the park","A book of bio","18","shakespeareinthepark.pdf","pdf","0","2147483647");
INSERT INTO library_contents VALUES("2","Times New","A book of bio","19","times.pdf","pdf","0","2147483647");
INSERT INTO library_contents VALUES("3","Torabi CV","CV of Someone","19","Tawsif Turabi CV.pdf","pdf","0","2147483647");
INSERT INTO library_contents VALUES("4","Sajjad Amin Resume","Sajjad Amin Resume Demo","21","sajjad-amin-resume.pdf","pdf","3","2147483647");
INSERT INTO library_contents VALUES("5","Project Guideline – Summer 2022","Project Guideline – Summer 2022","19","Project_Guideline.pdf","pdf","0","2147483647");
INSERT INTO library_contents VALUES("6","Sample Java Problem","Department of Computer Science and Engineering","19","Assignment-01.pdf","pdf","1","2147483647");
INSERT INTO library_contents VALUES("7","Fat Donkey Low Res","Demo Video","21","sample-mp4-file.mp4","video","2147483647","12");
INSERT INTO library_contents VALUES("8","UIUTFC Intro Tokkor","Tokkor Video","19","UIU Theatre & Film Club - Facebook.mp4","video","6","2147483647");
INSERT INTO library_contents VALUES("10","Facebook Day Tracker","A Facebook Day","19","Facebook.mp4","video","9","2147483647");

CREATE TABLE `library_download` (
  `download_id` int(11) NOT NULL AUTO_INCREMENT,
  `lecture_id` int(11) NOT NULL,
  `downloader` int(11) NOT NULL,
  `download_time` datetime NOT NULL,
  PRIMARY KEY (`download_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `library_lecture` (
  `lecture_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `course_name` varchar(30) NOT NULL,
  `faculty_name` varchar(30) NOT NULL,
  `uni_name` varchar(30) NOT NULL,
  `trimester` varchar(20) NOT NULL,
  `lecture_year` year(4) NOT NULL,
  `lecture_file` varchar(30) NOT NULL,
  `uploader_id` int(11) NOT NULL,
  `lecture_visibility` tinyint(1) NOT NULL DEFAULT 1,
  `lecture_upload_time` datetime NOT NULL,
  PRIMARY KEY (`lecture_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `library_review` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `lecture_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `review` mediumtext NOT NULL,
  PRIMARY KEY (`review_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

