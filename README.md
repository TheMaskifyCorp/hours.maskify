## Maskify Hour Registration Application

A school project to get started with PHP-OOP.
Current version: 0.4.1

### Installation

**Prequisites:**
Apache Server with Mod_rewrite enabled
MySQL database

**Installation**

Install at a [FQDN](https://en.wikipedia.org/wiki/Fully_qualified_domain_name) or locally at */localhost* by cloning this git.  
  
```git clone https://github.com/TheMaskifyCorp/hours.maskify.git```

Run the installer, and login.  
Default user:```admin@maskify.nl ```   
Default password: ```123456768```

### Mandatory frontend options

#### Search
<img src="./app/uploads/readme/search.gif" alt="search"/>  

#### Save unfound searches in database  
<img src="./app/uploads/readme/searchNotFound.gif" alt="searchnotfound"/>

#### Login as admin
<img src="./app/uploads/readme/login.gif" alt="login">

#### Change content by uploading csv
<img src="./app/uploads/readme/uploadEmp.gif" alt="csv-upload">

#### Delete unfound searches
<img src="./app/uploads/readme/deleteSearch.gif" alt="delete-searchresults">

#### Upload an image
<img src="./app/uploads/readme/upload404.gif" alt="upload-image">

### Further requirements:

#### Multi-lingual:
The application is available in Dutch and English, and a language can be picked in the menu-bar.

#### Responseform
A responseform is available on the contact.php page, and uses php-mailer to send mail.
To use this, please enter SMTP-data in the .env file.