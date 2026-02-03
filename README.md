# Blogging-Platform-CMS

Project Overview  
This project is based on building a Blogging Platform CMS using different technologies such as: PHP, MySQL, HTML, CSS, JS and AJAX.  
It allows authorized users to manage internal features like: create, delete, post while public views can read and comment on the posts.  
  
The system aims to focus on:  
•	Security (CSRF, prepared statements, authentication)  
•	Usability (Live search, Load More, filters)  
•	Role-Based Access Control (RBAC)  
•	Clean UI with mobile responsive design  
  
1. Login Credentials  
1.1.Admin Account I  
I.	Username: admin  
II.	Password: admin123  
1.	2.Admin Account II  
I.	Username: Eujina  
II.	Password: Deepshikha@123  
2.	Setup Instructions  
2.1.For Users  
I.	Index.php page  
Access to public page only to read view all the blog contents created by admin or authors.  
II.	View all the posts  
III.	Access to search posts by category, title. Access to advanced search as well.  
IV.	Users can read the contents inside the blog and has feature of comments to provide a review to the author.  
  
2.2.For admins  
I.  Full access for all the pages to create, update, delete and read. II.  	Controlled CMS platform III.  	Access to Login page:  
IV.  Provide correct username and password. V.  	Navigated to dashboard page.  
VI.  	From dashboard page, admin can get access to create, delete, or edit their contents.  
2.3.Login Page:  
  
   
  
2.4.Dashbaord.php:  
• Access to adding post, managing post and log out   
  
  
   
  
  
  
  
2.5.Add post page:  
• Add post including title, category, contents, and tags according to the author or admin.  
  
  
  
  
2.6.Manage page:  
• Admin can edit and delete the contents or posts if necessary.  
   
   
  
3. Features Implemented  
3.1. Authentication & Security  
•	Proper Login system with sessions  
•	Password hashing & verification  
•	CSRF protection for forms  
•	Role-Based Access Control (RBAC)  
•	SQL Injection  
•	XSS  
3.2.Content Management  
•	Add or create a new post  
•	Edit post  
•	Delete post  
•	Manage posts with edit and delete feature  
3.3.Categories & Tags  
•	Create categories (Admin only)  
•	Create tags (Admin only)  • 	Assign tags to posts  
3.4.Comments  	
• 	Users can add comments  
• 
3.5.Search & AJAX  	CSRF protection on comment form  
• 	Advanced search (by title + author)  
• 	AJAX Live Search (real-time)  
• 
3.6.UI and CSS  	Load More posts using Fetch API  
	• 	Responsive layout  
•	Flash success messages  
  
  
4. Technologies Used  
	Layer  	Technology  
	Frontend  	HTML5, CSS3, JS  
	Backend  	PHP (Procedural + PDO)  
	Database  	MySQL  
	Security  	CSRF Tokens, Sessions and Password Hashing.  
Server  	XAMPP / Apache / Student Server     
  	  
5. Known Issues  
•	Minor CSS differences on student and college server.  
•	No user registration (accounts are pre-created).  
  
6. Notes  
•	This project follows procedural PHP (no frameworks) as required  
•	All database queries use PDO prepared statements  
•	AJAX  is applied using Fetch API  
•	Security handled with sessions, CSRF, and RBAC  
  
7. Author  
•	Name: Deepshikha  
•	Module: Full Stack Development  
•	Site Project: Blogging Platform CMS  
  
  
 
8. Appendix  
College Server Link:  https://student.heraldcollege.edu.np/~np03cs4a240322/public/ 
GitHub Link:  
https://github.com/Deepshikha117/Blogging-Platform-CMS 
 
 
  
  
  
  
