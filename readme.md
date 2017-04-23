# Ticket Beast

## Section 1

1. Deciding what features to use
2. Building first Feature test, end to end test.
3. Running first test
    - setting up sqlite in memory DB
4. Unit testing the Concert model
5. Refactoring Code while keeping tests passing
6. Add more features to the Feature Test
7. Refactoring Controller using Concert Unit testing
8. Applying Model factory states to create more readable tests

> Remember to refactor test names as code develops to be more descriptive of the test as the test set builds up

## Section 2

1. Pros on cons of end to end browser and ened to end point Testig
2. Setting up payment Test
3. 
4. Using a fake to mock a third party appliction
5. Create ConcertOrders Controller and test driving first order create. Migrations for Orders and Tickets tables +
relationships created
6. Add test for json request validation and refactoring of the ConcertOrdersController
7. Add more test for request validation. Interesting ideas for testing different validation rule replies using the
assertArrayKey and the Json Decode method. See PurchaseTicketsTest
8. Refactoring the PurchaseTicketsTest to be more expressive, using helper functions to wrap commonly used items
such as the post request made. The assertions wee also encapsulated. This is a great technique for creating more
readable test.
9. Shows way of using try catch to assert tests past rather than using @exception notation in doc blocks of the
test. This method allows for further assertions to be made on the exception however this is not shown in this video,
only discussed. A custom Exception handler is created and try catch blocks added to the controller to throw new
exception. This is then tested for.
10. The test were updated to hand published and unpublished concerts with a test to see that unpublished concerts
couldn't have tickets bought for them. The controller method for storing the ticket methods has been updated to
 prioritise exceptions as several exceptions can be thrown in that controller.
