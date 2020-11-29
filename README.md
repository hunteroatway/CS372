# CS372
A project created for CS 372 - Software Engineering Methodology course.

## Set up
In order to set up this website on your own server, you will need to transition to your web servers directory.
Once in the location run
```
git clone https://github.com/hunteroatway/CS372.git
```
Then you will need to grant the correct permissions to all of the folders. Initially run 
```
chmod 711 CS372/
``` 
to grant the correct permissions to the main directory. Once inside the CS372 directory run **./perm.sh** in order to give the subdirectories and files the correct permissions.

## How To Use
On this website, there is different functionality based on whether the user is logged in or not.

### Not Logged In
When the user is not logged in, many of the features will not exist for the user. The user will be able to log in, sign up to create a new account, or search/ view listings without being able to message the seller.
To sign up, the user will have to submit a unique email address and username, as well as a password with at least 8 characters with at least one lowercase, one uppercase and one number, their first and last name, date of birth, location, as well as avatar image.

### Logged In
When the user is logged in, they have access to all of the features of the website. Similar to above, the user is able to search/ view listings based upon a selected location and search terms. Once selecting a listing they want to view they are able to go to the listings page to get more information on the book. Here they are able to open a message/ create a new message to send to the user. This will take them to the messaging page where the user can send a message to the seller and the seller is able to respond to the buyer.

The user is now able to post a listing of their own by navagating to the posting page via the "Post Ad" icon on the top naviagation bar. Here the user can input the ISBN value of their book and have the details automatically populated. Then the user just has to fill in the books quality and the asking price. If the user prefers a manual approach, they can also input all of the necessary information for the book themseleves. Once the listing has been posted, the seller is able to view the listing and go to the open chats for the listing as well mark the book as sold so it no longer appears in the search results. 

Lastly, the user is able to go to their profile page to get to the most recent chat they are a part of, view all of their active listings and modify their information to change password, names, location or avatar. 

## API's Used
This project used the following API's:
 - [Google Books](https://developers.google.com/books "Google Books API") In order to allow the user to simply input the ISBN of the textbook so that the website can automatically populate the information on the book.
 - [LocationIQ](https://locationiq.com/ "LocationIQ") In order to provide autocompletion on locations in order to help speed up the user inputting their location and ensuring that all of the information is stored accurately and consistently.

## Link
Link to our website:
 - [Pick-A-Book](http://www2.cs.uregina.ca/~ottenbju/CS372/pages/index.php "Pick-A-Book")