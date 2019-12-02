# Changelog

This document contains the registry of the changes between versions.

## 1.0.0-alpha.1

- Added migration system.

## 1.0.0-alpha.0

Added API basic modules:

- Added an authentication module. This module allow the client to register users and log in users with username / 
password, using the [Google Sign In API](https://developers.google.com/identity) or the 
[Facebook Sign In API](https://developers.facebook.com/docs/facebook-login/).
- Added a calendar module to create and read tasks and subtasks.
- Added a person module able to create legal guardians and kids. This module also allows to request and approve kid
relations, list pending relations to approve and list kid's associated to the current user.
