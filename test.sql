-- ----------------------------------------------------------------------------
-- to run in mySQL
-- log in as root user to mysql shell CLI
-- do not use (select) a database (do not 'USE VIMS' this script does that)
-- use the SOURCE command eg. mysql> SOURCE C:\Users\James\Desktop\test.sql;
-- ----------------------------------------------------------------------------


-- Select database
USE vims;

-- ----------------------------------------------------------------------------
-- Creating test data for user table
-- ----------------------------------------------------------------------------

INSERT INTO USER (USE_Name, USE_passwd, USE_Fname, USE_Lname, USE_Creator)
		   VALUES('almighty', MD5('genesis'), 'James', 'Smith', '1000');

INSERT INTO USER (USE_Name, USE_passwd, USE_Fname, USE_Lname, USE_Creator)
		   VALUES('jpsmith', MD5('jamespw'), 'James', 'Smith', '1000');

INSERT INTO USER (USE_Name, USE_passwd, USE_Fname, USE_Lname, USE_Creator)
		   VALUES('jwerre', MD5('justinpw'), 'Justin', 'Werre', '1000');

INSERT INTO USER (USE_Name, USE_passwd, USE_Fname, USE_Lname, USE_Creator)
		   VALUES('mclyke', MD5('maxwellpw'), 'Maxwell', 'Clyke', '1000');

INSERT INTO USER (USE_Name, USE_passwd, USE_Fname, USE_Lname, USE_Creator)
		   VALUES('tfaoro', MD5('tylorpw'), 'Tylor', 'Faoro', '1000');


