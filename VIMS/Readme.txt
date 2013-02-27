I have only started on this, if there are any issues please let me know.
- I wanted to make the login system into a class so that it would make the index.php page more simple to read.
- Index.php can be split into several "Action Cases" so the URL would be, for example, "?d=0&action={switch case}"
- News can become its own class, possibly called news.class.php which would manage Create, Modify, and Display News (Display could be a function called within the index.php)
for example here is my basic idea for the stucture.

===========================================
INCLUDE
	--> login.class.php
	--> news.class.php
	--> user.class.php
	
index.php  <-- will have one include for config.php, and will have a possible switch for actions.
config.php <-- will contain all include statements for all associated classes and related files (such as domain information, etc).

===========================================

That is really all I have atm. However, I do believe we should put some thought into this idea. If we go with an Object Oriented approach, I firmly believe it will give us
stronger level cohesion. If we do it in this method there will be a fair bit more SQL required, which could take more time; we will have to write more queries in order to have this information accessed
dynamically. I also believe that our code will be easier to read and manage if we use OOP, as opposed to a sequential strategy. 

An Object Oriented strategy will allow us to program in a Java(ish) language, which is a language we all may possibly be more familiar with.


