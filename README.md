# Pick-A-Book
Pick-A-Book is a online classifed web application specifically designed for the re-sale of textbooks. Current classified pages are general and provide the ability to post any item for sale. A general classifieds website poses changes for textbook re-sale because it can make it difficult to verify you are getting the right book that you need. Our website provides a unique experience that is specific to textbook re-sale, which allows us to implement book specific features that make for a much faster and enjoyable user experience both for buyers and sellers. 

## Setup
In order to host this website on your own server, you will need to transition to your web servers root directory. Once in the root directory, clone the directoy by running the following command:
```
git clone https://github.com/hunteroatway/CS372.git
```
You will need to ensure the directories have the correct permissions to allow access via a web browser. Running the following command provides the correct permissions to the main directory. 
```
chmod 711 CS372/
``` 
Once inside the CS372 directory run **./perm.sh** in order to give the sub-directories and files the correct permissions.

## How To Use
### Homepage
On the homepage (**index.html**), you will see the most recent postings to the database without being logged in. They are viewable, but to message the seller you will need to create an account.

### Login / Signup
You can create an account at the **signup.php** page. Once an account has been created you are able to login with your email and password using the **login.php** page. Having an account provides you access to many features that aren't available to non-account users, such as creating posting, managing your account and messaging other users.

Once you have created an account and are logged into the site, you can view your active posting's, view chat messages from other users looking to purchase your textbooks and manage your account. 

### Create a Posting
Creating a new posting is easy. It can be done at the **posting.php** page. Within the page there are two modes, manual and automatic which specify which information needs to be entered by the user.
* Manual Mode: In the manual mode, users are required to enter all the details regarding the book to create an advertisement. This includes, ISBN, title, subtitle, author(s), description, publisher, price, condition, etc.
* Automatic Mode: In automatic mode, users are only required to enter the ISBN of the book, price, condition and upload images of their physical copy. All other information is pull from the Google Books API. (Note: If the book does not pull an ISBN from the API, it is not considered a recognized book and will not be able to be posted)

### Messaging Users
Pick-A-Book offers an in-app messaging system that enables user to send and receive messages regarding a specific posting. This features enables users to message regarding individual books and negotiate prices, without the confusion of which book the user is looking for when compared to other classified websites. 

## API's Used
This project uses the following API's:
 - [Google Books](https://developers.google.com/books "Google Books API") In order to allow the user to simply input the ISBN of the textbook so that the website can automatically populate the information on the book.
 - [LocationIQ](https://locationiq.com/ "LocationIQ") In order to provide auto-completion on location information in order to help speed up the user inputting their location and ensuring that all of the information is stored accurately and consistently.

## Link
Link to our website:
 - [Pick-A-Book](http://www2.cs.uregina.ca/~ottenbju/CS372/pages/index.php "Pick-A-Book")

## Our Info
Our student ID Numbers:
 - Justin Ottenbreit - 200251932
 - Hunter Oatway - 200378986
 - Patrick LeBlanc - 200374786
 - Muhammad Hamza Imtiaz - 200366379
 - Subah Turna - 200357563