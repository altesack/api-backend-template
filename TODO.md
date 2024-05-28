# TODO LIST OF KNOWN ISSUES
====================================

## CreateUserAction
* No unit tests
* persist+flush I'd rather move to UserRepository
* Naming 

## Logout
* Logout Api method returns redirect instead of json
* JWT token probably should be removed from the DB on logout action

## API Tests
* No CRUD tests
* All Api tests are marked as Risky
* Tests work on live app data. Fixtures to be created
* Tests should be wrapped into transaction 
* Permission Admin+Owner should be moved to voter
