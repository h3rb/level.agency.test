# level.agency.test
Y3t @n0th3r d@n9 t3st 4 l33t haxx0rs


# Test content

Thank you for your interest in Level Interactive.

Enclosed is one programming problem.  We ask that you read the description thoroughly then create a program to solve the problem.  For the solution we ask that you use Node/JavaScript or PHP.  You may not use any enhanced functionality of the language or any external libraries to solve the problem.  However, you may use external libraries or tools for building or testing purposes.  Specifically, you may use testing libraries to assist in your development.  

When developing your solution please create a git repository and commit like you would in a production application while adding features.  Once you’ve completed the test please deliver your test via one of the following methods:

1)	Upload the test to your Github account and then email the Github project link to bbillmaier@level.agency.  
2)	Zip up the project and send an email to bbillmaier@level.agency and include the zip file as an attachment.

Feel free to include a brief explanation of your design and assumptions along with your code.

Generally we allow 72 hours from the date that you receive this assignment to submit your code.  You may request more time if you need it.  Your submission will be used as an interview tool.  Level Interactive reserves the right to postpone your interview process if your code is not received.  Please treat this as a production level application and develop it like you would for a production environment.

Thanks for your interest in Level Interactive and good luck!















A rover has recently landed on a plateau on Mars by NASA.  This particular plateau is curiously rectangular and must be navigated by the rover so that the on-board cameras can have a complete view of the surrounding terrain to send back to Mission Control on Earth.

The rover’s position and location is represented by a combination of x and y co-ordinates and a letter representing one of the four cardinal compass points. The plateau is divided up into a grid system to simplify navigation.  An example position might be 5, 5, S, which means the rover is in the top left corner facing South.

The rover is control by a simple string of letters sent by NASA.  The letter commands consist of ‘L’, ‘R’, and ‘M’.  ‘L’ and ‘R’ tells the rover to rotate 90 degrees left or right without moving from the current spot.  ‘M’ tells the rover to move forward one grid point and keep the current heading.

Assume that the square directly North from (x, y) is (x, y + 1)

Input:
The first line of input is the upper-right coordinates of the plateau, the lower-left coordinates are assumed to be 0, 0. The second line of input pertains to how the rover has been deployed on the specified grid system. The last line is a series of instructions to tell the rover how to explore the area. 

The position is made up of two integers and a letter separated by spaces, corresponding to the x and y co-ordinates and the rover’s orientation.

The movement commands is made of a string consisting of ‘L’, ‘R’, ‘M’.

Output:
The output that should be displayed will represent the rovers final co-ordinates and heading.
```
Test Input:
•	Test One: 
5 5
1 2 N
LMLMLMLMM
•	Test Two:
5 5
3 3 E
MMRMMRMRRM

Expected Output:
Test One – 1 3 N
Test Two – 5 1 E
```




Well, I did not get offered the job of "Front End Developer", but I did run this test through Kiuwan.com Code Review and scored a 5/5 on Security.

[![Kiuwan](https://www.kiuwan.com/github/h3rb/level.agency.test/badges/security.svg)](https://www.kiuwan.com/github/h3rb/level.agency.test)
