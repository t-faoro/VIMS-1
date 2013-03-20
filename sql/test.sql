-- ----------------------------------------------------------------------------
-- Prepared by James P. Smith, Feb 2013
-- To run in mySQL:
-- 	log in as root user to mysql shell CLI
-- 	do not use (select) a database (do not 'USE VIMS' this script does that)
-- 	use the SOURCE command specifying the path name using forward slashes
--		eg. mysql> SOURCE C:/Users/James/Desktop/test.sql;
--  
-- ----------------------------------------------------------------------------


DROP USER 'vimsfrontend'@'localhost';
DROP USER 'vimsfrontend'@'%';

-- Create use for V.I.M.S. Web Portal

CREATE USER 'vimsfrontend'@'localhost' 
	 IDENTIFIED BY 'poweroverwhelming';
GRANT SELECT, INSERT, UPDATE ON vims.* TO 'vimsfrontend'@'localhost';
CREATE USER 'vimsfrontend'@'%'
	 IDENTIFIED BY 'poweroverwhelming';
GRANT SELECT, INSERT, UPDATE ON vims.* TO 'vimsfrontend'@'%';

-- Select database
USE vims;

-- Clear database
DELETE FROM news_region_assc;
DELETE FROM news;
DELETE FROM incident_entry;
DELETE FROM var;
DELETE FROM venue_user_assc;
DELETE FROM venue;
DELETE FROM user;
DELETE FROM auth_level_lookup;
DELETE FROM involvement_lookup;
DELETE FROM incident_level_lookup;
DELETE FROM region;

-- Begin test data INSERT statements

-- ----------------------------------------------------------------------------
-- Test data for vims.region
-- ----------------------------------------------------------------------------

INSERT INTO region (REG_ID, REG_Name)
			 VALUES(101,   'Lethbridge');

INSERT INTO region (REG_ID, REG_Name)
			 VALUES(102,   'Calgary SW');

INSERT INTO region (REG_ID, REG_Name)
			 VALUES(103,   'Medicine Hat');

INSERT INTO region (REG_ID, REG_Name)
			 VALUES(104,   'Calgary NE');

INSERT INTO region (REG_ID, REG_Name)
			 VALUES(099,   'System');
 
 -- ----------------------------------------------------------------------------
 -- Test data for vims.incident_level_lookup
 -- ----------------------------------------------------------------------------
 
INSERT INTO incident_level_lookup (ILL_Level, ILL_Def)
						   VALUES (1, 		  'Notable');

INSERT INTO incident_level_lookup (ILL_Level, ILL_Def)
						   VALUES (2, 		  'Minor');

INSERT INTO incident_level_lookup (ILL_Level, ILL_Def)
						   VALUES (3, 		  'Serious');

INSERT INTO incident_level_lookup (ILL_Level, ILL_Def)
						   VALUES (4, 		  'Severe');

 -- ----------------------------------------------------------------------------
 -- Test data for vims.involement_lookup
 -- ----------------------------------------------------------------------------
 
INSERT INTO involvement_lookup (INV_Level, INV_Def)
							VALUES (1, 		  'Witness');

INSERT INTO involvement_lookup (INV_Level, INV_Def)
							VALUES (2, 		  'Victim');

INSERT INTO involvement_lookup (INV_Level, INV_Def)
							VALUES (3, 		  'Instigator');

INSERT INTO involvement_lookup (INV_Level, INV_Def)
							VALUES (4, 		  'Agressor');

 
-- ----------------------------------------------------------------------------
-- Test data for vims.auth_level_lookup
-- ----------------------------------------------------------------------------

INSERT INTO auth_level_lookup (AUT_Level, AUT_Def)
						VALUES(0,		 'Clubwatch');

INSERT INTO auth_level_lookup (AUT_Level, AUT_Def)
						VALUES(1,		 'Owner');

INSERT INTO auth_level_lookup (AUT_Level, AUT_Def)
						VALUES(2,		 'Supervisor');


-- ----------------------------------------------------------------------------
-- Test data for vims.user
-- ----------------------------------------------------------------------------

ALTER TABLE user AUTO_INCREMENT=1000;

INSERT INTO user (USE_Name, USE_passwd, USE_Fname, USE_Lname, USE_Creator)
		   VALUES('almighty', MD5('genesis'), 'James', 'Smith', 1000);

INSERT INTO user (USE_Name, USE_passwd, USE_Fname, USE_Lname, USE_Creator)
		   VALUES('jpsmith', MD5('jamespw'), 'James', 'Smith', 1000);

INSERT INTO user (USE_Name, USE_passwd, USE_Fname, USE_Lname, USE_Creator)
		   VALUES('jwerre', MD5('justinpw'), 'Justin', 'Werre', 1000);

INSERT INTO user (USE_Name, USE_passwd, USE_Fname, USE_Lname, USE_Creator)
		   VALUES('mclyke', MD5('maxwellpw'), 'Maxwell', 'Clyke', 1000);

INSERT INTO user (USE_Name, USE_passwd, USE_Fname, USE_Lname, USE_Creator)
		   VALUES('tfaoro', MD5('tylorpw'), 'Tylor', 'Faoro', 1000);


-- ----------------------------------------------------------------------------
-- Test data for vims.venue
-- ----------------------------------------------------------------------------

ALTER TABLE venue AUTO_INCREMENT=100;

INSERT INTO venue (VEN_Name, Region_REG_ID)
		   VALUES('Clubwatch', 099);
		   
INSERT INTO venue (VEN_Name, VEN_Unit_Addr, VEN_St_Addr, VEN_City, VEN_Province, VEN_Pcode, VEN_Phone, VEN_Liason, Region_REG_ID)
		   VALUES ('The Drunkery', '101', '123 1st St', 'Lethbridge', 'Alberta', 'T1H1R1', '4033101010', 'Catelyn Tully', 101);
		   
INSERT INTO venue (VEN_Name, VEN_Unit_Addr, VEN_St_Addr, VEN_City, VEN_Province, VEN_Pcode, VEN_Phone, VEN_Liason, Region_REG_ID)
		   VALUES ('The Party Room', '3B', '4040 5th Ave SW', 'Calgary', 'Alberta', 'T1H1R1', '4033101010', 'Roose Bolton', 102);
		   
-- ----------------------------------------------------------------------------
-- Test data for vims.venue_user_assc
-- ----------------------------------------------------------------------------

INSERT INTO venue_user_assc (Venue_VEN_ID, User_USE_ID, Auth_Level_Lookup_AUT_Level)
					 VALUES (100,		   1000,		0);
 
INSERT INTO venue_user_assc (Venue_VEN_ID, User_USE_ID, Auth_Level_Lookup_AUT_Level)
					 VALUES (100,		   1001,		0);
 
INSERT INTO venue_user_assc (Venue_VEN_ID, User_USE_ID, Auth_Level_Lookup_AUT_Level)
					 VALUES (101,		   1002,		1);
					 
INSERT INTO venue_user_assc (Venue_VEN_ID, User_USE_ID, Auth_Level_Lookup_AUT_Level)
					 VALUES (100,		   1002,		0);
					 
INSERT INTO venue_user_assc (Venue_VEN_ID, User_USE_ID, Auth_Level_Lookup_AUT_Level)
					 VALUES (102,		   1002,		1);
 
INSERT INTO venue_user_assc (Venue_VEN_ID, User_USE_ID, Auth_Level_Lookup_AUT_Level)
					 VALUES (101,		   1003,		2);
 
INSERT INTO venue_user_assc (Venue_VEN_ID, User_USE_ID, Auth_Level_Lookup_AUT_Level)
					 VALUES (102,		   1004,		1);
 
-- ----------------------------------------------------------------------------
-- Test data for vims.var
-- ----------------------------------------------------------------------------

ALTER TABLE var AUTO_INCREMENT=1;
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-02-06', 165, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-02-07', 170, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);

INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-02-08', 254, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-02-09', 284, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-02-10', 190, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-02-13', 195, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-02-14', 190, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-02-15', 180, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-02-16', 213, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-02-17', 234, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-02-20', 170, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-02-21', 160, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-02-22', 155, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-02-23', 170, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-02-24', 254, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-02-27', 165, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-02-28', 170, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-03-01', 188, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-03-02', 210, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-03-03', 288, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-03-06', 170, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
 INSERT INTO var (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor, VAR_Event, Venue_VEN_ID, User_USE_ID)
		 VALUES ('2013-03-07', 140, 1, 'Maxwell Clyke', 'Reg. Op.', 101, 1003);
		 
-- ----------------------------------------------------------------------------
-- Test data for vims.incident_entry
-- ----------------------------------------------------------------------------

ALTER TABLE incident_entry AUTO_INCREMENT=1;

INSERT INTO incident_entry (Var_VAR_ID, INE_Time, INE_Police, INE_Content, INE_Damages, Incident_Level_Lookup_ILL_Level)
					VALUES (10,'17:32:00',0,'Some guy walked into the bar and said ouch', 'Minor damage to bar', 1);

INSERT INTO incident_entry (Var_VAR_ID, INE_Time, INE_Police, INE_Content, INE_Damages, Incident_Level_Lookup_ILL_Level)
					VALUES (13,'17:32:00',0,'Duck walked into the bar and asked about grapes. Was asked to leave.'
					, '', 1);
					
INSERT INTO incident_entry (Var_VAR_ID, INE_Time, INE_Police, INE_Content, INE_Damages, Incident_Level_Lookup_ILL_Level)
					VALUES (13,'17:48:00',1,'Duck walked into the bar and asked about grapes again. Bartender nailed his stupid duck beak to the bar'
					, 'Minor damage to bar', 1);

-- ----------------------------------------------------------------------------
-- Test data for vims.NEWS
-- ----------------------------------------------------------------------------

ALTER TABLE news AUTO_INCREMENT=1;

INSERT INTO news (NEW_Date, NEW_Title, NEW_Content, NEW_Type, User_USE_ID)
					VALUES ('2013-03-03 00:00:00', 'New Regulations','Industry Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer et accumsan elit. Nunc dolor eros, interdum at tristique ut, imperdiet nec dui. Suspendisse potenti. Vivamus elementum lobortis mi, eu egestas velit dapibus eu. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer et accumsan elit. Nunc dolor eros, interdum at tristique ut, imperdiet nec dui. Suspendisse potenti. Vivamus elementum lobortis mi, eu egestas velit dapibus eu. ', 1, 1001);
					
INSERT INTO news (NEW_Date, NEW_Title, NEW_Content, NEW_Type, User_USE_ID)
					VALUES ('2013-03-03 00:00:00', 'Cool Club stuff happening', 'Clubwatch Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer et accumsan elit. Nunc dolor eros, interdum at tristique ut, imperdiet nec dui. Suspendisse potenti. Vivamus elementum lobortis mi, eu egestas velit dapibus eu. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer et accumsan elit. Nunc dolor eros, interdum at tristique ut, imperdiet nec dui. Suspendisse potenti. Vivamus elementum lobortis mi, eu egestas velit dapibus eu. ', 2, 1001);
					
INSERT INTO news (NEW_Date, NEW_Title, NEW_Content, NEW_Type, User_USE_ID)
					VALUES ('2013-03-04 00:00:00', 'Old Regulations', 'Industry Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer et accumsan elit. Nunc dolor eros, interdum at tristique ut, imperdiet nec dui. Suspendisse potenti. Vivamus elementum lobortis mi, eu egestas velit dapibus eu. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer et accumsan elit. Nunc dolor eros, interdum at tristique ut, imperdiet nec dui. Suspendisse potenti. Vivamus elementum lobortis mi, eu egestas velit dapibus eu. ', 1, 1001);
					
-- ----------------------------------------------------------------------------
-- Test data for vims.news_region_assc
-- ----------------------------------------------------------------------------

INSERT INTO news_region_assc (News_NEW_ID, Region_REG_ID)
					   VALUES(1, 101);
					   
INSERT INTO news_region_assc (News_NEW_ID, Region_REG_ID)
					   VALUES(2, 101);
					   
INSERT INTO news_region_assc (News_NEW_ID, Region_REG_ID)
					   VALUES(3, 101);
					   
