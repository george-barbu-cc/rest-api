# Task description

We have a segment of our team that takes care of a very special customer. This customer handles a lot of contracts with most of the EU contries and has big reputation. Since it has high value to us, we agreed to build a custom module in our system in which we may be able to handle custom data provided by them.

This data consists of details about salesmens' contacts and sales. It's provided in a CSV file format. The amount of records may vary between a few hundred to tens of thousands.

The objective of this module is to provide our internal team with more granular data. We are expected to receive this file twice a week, although we may receive it more often with no short notice. As soon as this file is received, our internal team would like to start working with it's processed data in no time.


# File description

- Each line of the file represents a contact with a customer (take a look at the repository - there is a sample file)
- Lines with contact data but missing sales data represents a contact with a customer, either by a visit or call, but no sale accomplished (conversion).
- UUID is unique per line and identifies one data point.


# Technical task description

Design and implement a restful API which may be able to receive the received file from our customer (sample file provided), arrange and store the data and make it available from the API.

We want to have all the data points from the file stored in a database and exposed in the API.
Data is categorized in 3 kinds: Seller, Contact and Sale.

Endpoints should have the following specifications:

- `/load`: Upload the file
- `/sellers/{id}`: Provide complete seller data via id
- `/sellers/{id}/contacts`: Provide a list of all contacts established by the seller.
- `/sellers/{id}/sales`: Provide a list of all sales data accomplished by the seller.
- `/sales/{year}`: Provide an object with two properties: First property as an object with calculated properties (netAmount, grossAmount, taxAmount, profit, % profit) for the period; Second property with list of all sales matching the period.

Profit is considered by the following formula: Gross amount - Tax - Cost.

We should consider that our high-estimated customer may send:

- The same file twice
- Repeated records in a single file
- Records send in current file but already sent in the previous file
- Records with missing UUID

# Should stick to the follwing guidelines

- Docker as container management tool
- MySQL or MariaDB as relational database
- Use an ORM
- Use migrations or provide an SQL-file to initialize the database
- Framework of your choice. Must use a PHP framework. Composer as package management.
- Avoid using external libraries other than Doctrine and framework-provided ones. Using extra services/tools, like another database provider, broker/queue or caching system, is allowed
- APIs must expose only JSON
- Automated testing must be implemented

# Project delivery

- Project must have clear instructions on how to set up, install dependencies and run.
- Git should be used as a VCS. Please send us the link to the repository or a zip-file.
- Feel free to ask questions regarding the requirements.
