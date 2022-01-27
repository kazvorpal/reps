3-12-2019
Version 1.0

Released without notes

######################

8/9/2019
Version 1.1.0


1.  Level 2 data added to RePS and Detailed Phase Report
	a.  Uses JQuery Colorbox	
2.  Risk and Issues Columns added and display number of risk and issues for both program and project to RePS and Detailed Phase Report
	a.  JQuery Colorbox used to display multiple risk or issues
	b.  Open new window to display risk or issue at EPS Sharepoint Site
3.  No access items added for users with no permission to Sharepoint or EPS and displayed in JQuery Colorbox to RePS and Detailed Phase Report
	a.  Project schedule 
	b.  Risk or Issue Project
	c.  Risk or Issues Program
4.  Project counts added to to RePS and Detailed Phase Report
5.  Fiscal Year added to Detailed Phase Report
6.  POR migrated from Excel
	a.  Built in Javascript
	b.  Only works in Google Chrome
	c.  Loading screen does not work due to conflicts with JQuery Library
	d.  Download feature missing Ghantt chart
7.  Oracle start and end dates added to RePS, EPS Status Report and Detailed Phase Report
8.  Equipment typs and counts added to  to RePS, EPS Status Report and Detailed Phase Report
9.  Color Legends added to RePS and Detailed Phase Report
10.  Fiscal year filter added to Detailed Phase Report


######################

10/14/2019
Version 2.0.0

1.  Data source switched from MySQL to MSSQL Server (Production COX and ODS)
1.1 Reports Moved to Development VM and Production VM
2.  Connection updated to include encrypted passwords for security.  Used encryption class in funtions.php
3.  sqlsrv fetch used to capture data from SQL Server
4.  All print to screen echos neutralized for security by htmlspecialchars and urlencode functions
5.  Preformance is 60% faster
6.  Table headers locked in DPR and EPS Status reports with position: sticky in colorbox.css and applied to <th> tags
7.  VeraScan Security passed 100%

######################

10/29/2019
Version 2.1.0

1.  Added Order History Search
2.  Added Order History by linking it to Oracle Code in Regional View, DPR and EPS Status Reports
3.  Risk and Issues column name Source of Impact changed to Impacted Areas
4.  Added Project Manager to Risk and issues Data
5.  POR2020 Added

######################

12/11/2019
Version 2.2.3

1.  Added CR Overview and CR Detailed View to include Plan Change
2.  Reformatted dates to m-d-Y from Y-m-d
3.  Added Fiscal Year filter to Regional View.  Resulted in 60% faster load times

######################

01/10/2020
Version 2.2.5

1.  POR2020 Download Button fixed
2.  Status dropdown corrected in CR Overview Sql query

######################

01/13/2020
Version 2.2.6

1.  Regional View missing program data fixed.

######################

01/14/2020
Version 2.2.7

1.  Risk and issues data connection moved to COX production 

######################

01/22/2020
Version 2.2.8

1.  POR2020 output fixed.  Encode the result set fetched in utf8 before streaming back the json response 

######################

03/5/2020
Version 2.2.9

1.  Equipment Order History Search, Oracle/Order code field fixed.  Strip out quotes and double quotes before sending to sql statement.


######################

03/5/2020 - 04/21/2020
Version 2.3.0 - 2.3.6

1.  Manual POR20 Downloads

######################

04/21/2020
Version 2.4.6

1.  All Sql function promoted to COX Database.  DPR and EPS Status Reports connections point to COX Database



######################

07/15/2020
Version 2.4.7

1. Order history sql updated
2. Subprogram added.  


######################

08/06/2020
Version 2.5.7

1. Order history Export Feature added
2. Order history backorder search added
3. Subprogram Filter added
4. Finish Date added to DPR and only showes after Execute Stage
5. Red Late Indicator for stages fixed and is red after the date has passed not on the date
6. Grey stages no long show dates





