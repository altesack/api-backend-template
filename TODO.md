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
* All Api tests are marked as Risky

## API
* Permission Admin+Owner should be moved to voter
* CRUD methods write not hashed password directrly to the DB
